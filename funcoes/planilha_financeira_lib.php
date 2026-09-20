<?php
/**
 * Cálculo financeiro alinhado à planilha:
 * Planilha_Financeira_Autorizacoes_Hospedagem_Village_Relatorio2.xlsx
 *
 * - Cobrança: 1 taxa por autorização emitida (não por hóspede / não por diária)
 * - Temporada: mês da DATA DE ENTRADA
 * - Filtro do período: data de EMISSÃO (dt_ultima_alteracao)
 * - Canceladas: Cobrar? = Sim por padrão
 */

function pf_parse_br_date($valor)
{
    $valor = trim((string) $valor);
    if ($valor === '') {
        return null;
    }
    $dt = DateTime::createFromFormat('d/m/Y', $valor);
    if ($dt instanceof DateTime) {
        $dt->setTime(0, 0, 0);
        return $dt;
    }
    $dt = DateTime::createFromFormat('Y-m-d', $valor);
    if ($dt instanceof DateTime) {
        $dt->setTime(0, 0, 0);
        return $dt;
    }
    return null;
}

function pf_parse_money($v, $default = 0.0)
{
    $v = trim((string) $v);
    if ($v === '') {
        return (float) $default;
    }
    $v = preg_replace('/[^\d,.\-]/', '', $v);
    if (strpos($v, ',') !== false && strpos($v, '.') !== false) {
        $v = str_replace('.', '', $v);
        $v = str_replace(',', '.', $v);
    } elseif (strpos($v, ',') !== false) {
        $v = str_replace(',', '.', $v);
    }
    return (float) $v;
}

function pf_format_money($valor)
{
    return 'R$ ' . number_format((float) $valor, 2, ',', '.');
}

function pf_etapa_abrev($etapa, $numero)
{
    switch ($etapa) {
        case 'Azaléia - AZ':
            return 'AZ/' . $numero;
        case 'Bougainville - BO':
            return 'BO/' . $numero;
        case 'Gardênia - GA':
            return 'GA/' . $numero;
        case 'Jacarandás - JAC':
            return 'JAC/' . $numero;
        case 'Orquídeas - OR':
            return 'OR/' . $numero;
        case 'Pitangueiras - PIT':
            return 'PIT/' . $numero;
        default:
            return trim($etapa . '/' . $numero, '/');
    }
}

function pf_mapa_padrao_2026()
{
    return array(
        '2026-01' => 'alta',
        '2026-02' => 'alta',
        '2026-03' => 'baixa',
        '2026-04' => 'alta',
        '2026-05' => 'baixa',
        '2026-06' => 'baixa',
        '2026-07' => 'alta',
        '2026-08' => 'baixa',
    );
}

function pf_montar_mapa_temporada($post)
{
    $mapa = array();
    if (!empty($post['temp_mes']) && is_array($post['temp_mes'])) {
        foreach ($post['temp_mes'] as $i => $ym) {
            $ym = trim($ym);
            if (!preg_match('/^\d{4}-\d{2}$/', $ym)) {
                continue;
            }
            $tipo = isset($post['temp_tipo'][$i]) ? $post['temp_tipo'][$i] : 'baixa';
            $mapa[$ym] = ($tipo === 'alta') ? 'alta' : 'baixa';
        }
    }
    if (empty($mapa)) {
        $mapa = pf_mapa_padrao_2026();
    }
    ksort($mapa);
    return $mapa;
}

function pf_temporada_do_mes($ym, $mapa, $default = 'baixa')
{
    if (isset($mapa[$ym])) {
        return $mapa[$ym];
    }
    return $default;
}

function pf_label_mes_pt($ym)
{
    static $meses = array(
        '01' => 'jan', '02' => 'fev', '03' => 'mar', '04' => 'abr',
        '05' => 'mai', '06' => 'jun', '07' => 'jul', '08' => 'ago',
        '09' => 'set', '10' => 'out', '11' => 'nov', '12' => 'dez'
    );
    $p = explode('-', $ym);
    if (count($p) !== 2) {
        return $ym;
    }
    $m = isset($meses[$p[1]]) ? $meses[$p[1]] : $p[1];
    return $m . '/' . $p[0];
}

function pf_buscar_autorizacoes($dtIni, $dtFim)
{
    $where = array();
    $where[] = " loc.dt_ultima_alteracao IS NOT NULL AND loc.dt_ultima_alteracao <> '' AND loc.dt_ultima_alteracao <> '0000-00-00 00:00:00' ";

    if ($dtIni) {
        $where[] = " DATE(loc.dt_ultima_alteracao) >= '" . mysql_real_escape_string($dtIni->format('Y-m-d')) . "' ";
    }
    if ($dtFim) {
        $where[] = " DATE(loc.dt_ultima_alteracao) <= '" . mysql_real_escape_string($dtFim->format('Y-m-d')) . "' ";
    }

    $sql = "SELECT loc.*, uni.etapa, uni.numero_etapa, uni.tipo_unidade, prop.nome
            FROM audita loc
            JOIN proprietario prop ON loc.id_proprietario = prop.id_proprietario
            JOIN unidade uni ON loc.id_unidade = uni.id_unidade
            WHERE " . implode(' AND ', $where) . "
            ORDER BY loc.dt_entrada ASC, loc.id_audita ASC";

    $rs = mysql_query($sql);
    $lista = array();
    if ($rs) {
        while ($row = mysql_fetch_assoc($rs)) {
            $lista[] = $row;
        }
    }
    return $lista;
}

/**
 * Processa linhas no modelo da planilha anexa.
 */
function pf_processar_linhas($registros, $valorAlta, $valorBaixa, $mapa)
{
    $linhas = array();
    $totais = array(
        'autorizacoes' => 0,
        'hospedes' => 0,
        'cobraveis' => 0,
        'receita' => 0.0,
        'por_mes' => array()
    );

    $seq = 0;
    foreach ($registros as $ln) {
        $seq++;
        $dtEmissao = null;
        if (!empty($ln['dt_ultima_alteracao'])) {
            $dtEmissao = DateTime::createFromFormat('Y-m-d H:i:s', $ln['dt_ultima_alteracao']);
            if (!$dtEmissao) {
                $dtEmissao = DateTime::createFromFormat('Y-m-d', substr($ln['dt_ultima_alteracao'], 0, 10));
            }
        }
        $dtEntrada = !empty($ln['dt_entrada']) ? DateTime::createFromFormat('Y-m-d', $ln['dt_entrada']) : null;
        $dtSaida = !empty($ln['dt_saida']) ? DateTime::createFromFormat('Y-m-d', $ln['dt_saida']) : null;

        $cancelada = (isset($ln['excluido_usuario']) && strtolower($ln['excluido_usuario']) === 'sim');
        $status = $cancelada ? 'Cancelada' : 'Ativa';

        // Temporada pelo mês da ENTRADA (como na planilha)
        $ymEntrada = $dtEntrada ? $dtEntrada->format('Y-m') : '';
        $temporada = $ymEntrada !== '' ? pf_temporada_do_mes($ymEntrada, $mapa, 'baixa') : 'baixa';
        $taxa = ($temporada === 'alta') ? $valorAlta : $valorBaixa;

        // Cobrar? = Sim por padrão (inclusive canceladas — critério emissão)
        $cobrar = 'Sim';
        $receita = ($cobrar === 'Sim') ? $taxa : 0.0;

        $unidade = pf_etapa_abrev($ln['etapa'], $ln['numero_etapa']);
        $hospedes = (int) $ln['qtde_hospedes'];
        $proprietario = $ln['nome'];

        $linha = array(
            'seq' => $seq,
            'id_audita' => $ln['id_audita'],
            'unidade' => $unidade,
            'proprietario' => $proprietario,
            'hospedes' => $hospedes,
            'entrada' => $dtEntrada ? $dtEntrada->format('d/m/Y') : '',
            'entrada_iso' => $dtEntrada ? $dtEntrada->format('Y-m-d') : '',
            'saida' => $dtSaida ? $dtSaida->format('d/m/Y') : '',
            'status' => $status,
            'mes' => $ymEntrada !== '' ? pf_label_mes_pt($ymEntrada) : '',
            'mes_ym' => $ymEntrada,
            'temporada' => ($temporada === 'alta') ? 'Alta' : 'Baixa',
            'cobrar' => $cobrar,
            'taxa' => $taxa,
            'receita' => $receita,
            'emissao' => $dtEmissao ? $dtEmissao->format('d/m/Y H:i') : ''
        );
        $linhas[] = $linha;

        $totais['autorizacoes']++;
        $totais['hospedes'] += $hospedes;
        if ($cobrar === 'Sim') {
            $totais['cobraveis']++;
            $totais['receita'] += $receita;
        }

        if ($ymEntrada !== '') {
            if (!isset($totais['por_mes'][$ymEntrada])) {
                $totais['por_mes'][$ymEntrada] = array(
                    'label' => pf_label_mes_pt($ymEntrada),
                    'autorizacoes' => 0,
                    'hospedes' => 0,
                    'receita' => 0.0
                );
            }
            $totais['por_mes'][$ymEntrada]['autorizacoes']++;
            $totais['por_mes'][$ymEntrada]['hospedes'] += $hospedes;
            $totais['por_mes'][$ymEntrada]['receita'] += $receita;
        }
    }

    ksort($totais['por_mes']);
    return array('linhas' => $linhas, 'totais' => $totais);
}
