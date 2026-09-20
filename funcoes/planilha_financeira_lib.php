<?php
/**
 * Cálculo financeiro alinhado à planilha:
 * Planilha_Financeira_Autorizacoes_Hospedagem_Village_Relatorio2.xlsx
 *
 * - Cobrança: 1 taxa por autorização emitida (não por hóspede / não por diária)
 * - Temporada: mês da DATA DE ENTRADA
 * - Alta padrão: janeiro, fevereiro, julho e dezembro
 * - Feriados nacionais emendados com o fim de semana também são alta temporada
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

function pf_ano_corrente()
{
    return (int) date('Y');
}

function pf_meses_alta_padrao()
{
    return array('01', '02', '07', '12');
}

function pf_nomes_meses()
{
    return array(
        '01' => 'Janeiro',
        '02' => 'Fevereiro',
        '03' => 'Março',
        '04' => 'Abril',
        '05' => 'Maio',
        '06' => 'Junho',
        '07' => 'Julho',
        '08' => 'Agosto',
        '09' => 'Setembro',
        '10' => 'Outubro',
        '11' => 'Novembro',
        '12' => 'Dezembro'
    );
}

function pf_mapa_padrao_ano($ano = null)
{
    $ano = $ano ? (int) $ano : pf_ano_corrente();
    $alta = pf_meses_alta_padrao();
    $mapa = array();
    for ($m = 1; $m <= 12; $m++) {
        $mm = str_pad((string) $m, 2, '0', STR_PAD_LEFT);
        $mapa[$ano . '-' . $mm] = in_array($mm, $alta, true) ? 'alta' : 'baixa';
    }
    return $mapa;
}

function pf_mapa_padrao_2026()
{
    return pf_mapa_padrao_ano(pf_ano_corrente());
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
        $mapa = pf_mapa_padrao_ano();
    }
    ksort($mapa);
    return $mapa;
}

function pf_nomes_semana()
{
    return array(
        0 => 'domingo',
        1 => 'segunda-feira',
        2 => 'terça-feira',
        3 => 'quarta-feira',
        4 => 'quinta-feira',
        5 => 'sexta-feira',
        6 => 'sábado'
    );
}

function pf_format_iso_br($iso)
{
    $dt = DateTime::createFromFormat('Y-m-d', $iso);
    return $dt ? $dt->format('d/m/Y') : $iso;
}

function pf_periodo_emenda_feriado($dt)
{
    $ini = clone $dt;
    $fim = clone $dt;
    $w = (int) $dt->format('w');
    $emendado = false;

    if ($w === 1) {
        $ini->modify('-2 days');
        $emendado = true;
    } elseif ($w === 2) {
        $ini->modify('-3 days');
        $emendado = true;
    } elseif ($w === 4) {
        $fim->modify('+3 days');
        $emendado = true;
    } elseif ($w === 5) {
        $fim->modify('+2 days');
        $emendado = true;
    } elseif ($w === 6) {
        $fim->modify('+1 day');
        $emendado = true;
    } elseif ($w === 0) {
        $ini->modify('-1 day');
        $emendado = true;
    }

    return array(
        'emendado' => $emendado,
        'ini' => $ini,
        'fim' => $fim
    );
}

function pf_normalizar_feriado($dataIso, $nome, $considerarAlta = null)
{
    $dt = DateTime::createFromFormat('Y-m-d', $dataIso);
    if (!$dt) {
        return null;
    }
    $dt->setTime(0, 0, 0);
    $periodo = pf_periodo_emenda_feriado($dt);
    $dias = pf_nomes_semana();
    $alta = ($considerarAlta === null) ? $periodo['emendado'] : (bool) $considerarAlta;
    $w = (int) $dt->format('w');

    return array(
        'data' => $dt->format('Y-m-d'),
        'nome' => $nome,
        'weekday' => isset($dias[$w]) ? $dias[$w] : '',
        'emendado' => $periodo['emendado'],
        'alta' => $alta,
        'alta_ini' => $periodo['ini']->format('Y-m-d'),
        'alta_fim' => $periodo['fim']->format('Y-m-d')
    );
}

function pf_http_get($url)
{
    $ua = 'ResidencialVillage/1.0';
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 6);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $out = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($out === false || $code < 200 || $code >= 300) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 6);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, $ua);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $out = curl_exec($ch);
            $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
        }
        if ($out !== false && $code >= 200 && $code < 300) {
            return $out;
        }
    }

    $ctx = stream_context_create(array(
        'http' => array(
            'timeout' => 10,
            'header' => "User-Agent: " . $ua . "\r\n",
            'ignore_errors' => true
        ),
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false
        )
    ));
    $out = @file_get_contents($url, false, $ctx);
    return ($out !== false) ? $out : '';
}

function pf_cache_feriados_path($ano)
{
    $dir = dirname(__FILE__) . '/../temp';
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
    return $dir . '/feriados_nacionais_' . (int) $ano . '.json';
}

function pf_ler_cache_feriados($ano, $maxIdade = 86400)
{
    $path = pf_cache_feriados_path($ano);
    if (!is_file($path)) {
        return null;
    }
    $raw = @file_get_contents($path);
    if ($raw === false || $raw === '') {
        return null;
    }
    $json = json_decode($raw, true);
    if (!is_array($json) || empty($json['lista']) || !is_array($json['lista'])) {
        return null;
    }
    $ts = isset($json['ts']) ? (int) $json['ts'] : 0;
    if ($maxIdade > 0 && $ts > 0 && (time() - $ts) > $maxIdade) {
        return null;
    }
    return $json;
}

function pf_gravar_cache_feriados($ano, $lista, $fonte)
{
    $payload = array(
        'ano' => (int) $ano,
        'fonte' => $fonte,
        'ts' => time(),
        'atualizado_em' => date('d/m/Y H:i'),
        'lista' => $lista
    );
    @file_put_contents(pf_cache_feriados_path($ano), json_encode($payload));
    return $payload;
}

function pf_pascoa($ano)
{
    $a = $ano % 19;
    $b = (int) ($ano / 100);
    $c = $ano % 100;
    $d = (int) ($b / 4);
    $e = $b % 4;
    $f = (int) (($b + 8) / 25);
    $g = (int) (($b - $f + 1) / 3);
    $h = (19 * $a + $b - $d - $g + 15) % 30;
    $i = (int) ($c / 4);
    $k = $c % 4;
    $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
    $m = (int) (($a + 11 * $h + 22 * $l) / 451);
    $mes = (int) (($h + $l - 7 * $m + 114) / 31);
    $dia = (($h + $l - 7 * $m + 114) % 31) + 1;
    $dt = DateTime::createFromFormat('Y-n-j', $ano . '-' . $mes . '-' . $dia);
    if ($dt instanceof DateTime) {
        $dt->setTime(0, 0, 0);
    }
    return $dt;
}

function pf_feriados_calculados($ano)
{
    $lista = array();
    $lista[] = array('data' => $ano . '-01-01', 'nome' => 'Confraternização mundial');

    $pascoa = pf_pascoa($ano);
    if ($pascoa instanceof DateTime) {
        $carnavalTer = clone $pascoa;
        $carnavalTer->modify('-47 days');
        $carnavalSeg = clone $carnavalTer;
        $carnavalSeg->modify('-1 day');
        $sextaSanta = clone $pascoa;
        $sextaSanta->modify('-2 days');
        $corpus = clone $pascoa;
        $corpus->modify('+60 days');

        $lista[] = array('data' => $carnavalSeg->format('Y-m-d'), 'nome' => 'Carnaval');
        $lista[] = array('data' => $carnavalTer->format('Y-m-d'), 'nome' => 'Carnaval');
        $lista[] = array('data' => $sextaSanta->format('Y-m-d'), 'nome' => 'Sexta-feira Santa');
        $lista[] = array('data' => $pascoa->format('Y-m-d'), 'nome' => 'Páscoa');
        $lista[] = array('data' => $corpus->format('Y-m-d'), 'nome' => 'Corpus Christi');
    }

    $lista[] = array('data' => $ano . '-04-21', 'nome' => 'Tiradentes');
    $lista[] = array('data' => $ano . '-05-01', 'nome' => 'Dia do trabalho');
    $lista[] = array('data' => $ano . '-09-07', 'nome' => 'Independência do Brasil');
    $lista[] = array('data' => $ano . '-10-12', 'nome' => 'Nossa Senhora Aparecida');
    $lista[] = array('data' => $ano . '-11-02', 'nome' => 'Finados');
    $lista[] = array('data' => $ano . '-11-15', 'nome' => 'Proclamação da República');
    $lista[] = array('data' => $ano . '-11-20', 'nome' => 'Dia da consciência negra');
    $lista[] = array('data' => $ano . '-12-25', 'nome' => 'Natal');

    return $lista;
}

function pf_parse_lista_brasilapi($raw)
{
    $json = json_decode($raw, true);
    if (!is_array($json)) {
        return array();
    }
    $lista = array();
    foreach ($json as $item) {
        if (!is_array($item) || empty($item['date']) || empty($item['name'])) {
            continue;
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $item['date'])) {
            continue;
        }
        $lista[] = array('data' => $item['date'], 'nome' => $item['name']);
    }
    return $lista;
}

function pf_parse_lista_nager($raw)
{
    $json = json_decode($raw, true);
    if (!is_array($json)) {
        return array();
    }
    $lista = array();
    foreach ($json as $item) {
        if (!is_array($item) || empty($item['date'])) {
            continue;
        }
        if (isset($item['global']) && $item['global'] === false) {
            continue;
        }
        $nome = '';
        if (!empty($item['localName'])) {
            $nome = $item['localName'];
        } elseif (!empty($item['name'])) {
            $nome = $item['name'];
        }
        if ($nome === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $item['date'])) {
            continue;
        }
        $lista[] = array('data' => $item['date'], 'nome' => $nome);
    }
    return $lista;
}

function pf_buscar_feriados_remotos($ano)
{
    $urlBrasil = 'https://brasilapi.com.br/api/feriados/v1/' . (int) $ano;
    $lista = pf_parse_lista_brasilapi(pf_http_get($urlBrasil));
    if (!empty($lista)) {
        return array('lista' => $lista, 'fonte' => 'BrasilAPI (feriados nacionais)');
    }

    $urlNager = 'https://date.nager.at/api/v3/PublicHolidays/' . (int) $ano . '/BR';
    $lista = pf_parse_lista_nager(pf_http_get($urlNager));
    if (!empty($lista)) {
        return array('lista' => $lista, 'fonte' => 'Nager.Date (feriados nacionais do Brasil)');
    }

    return array(
        'lista' => pf_feriados_calculados($ano),
        'fonte' => 'Calendário oficial brasileiro (cálculo local)'
    );
}

function pf_feriados_nacionais_ano($ano = null)
{
    $ano = $ano ? (int) $ano : pf_ano_corrente();
    $cache = pf_ler_cache_feriados($ano, 86400);
    if ($cache) {
        $listaNorm = array();
        foreach ($cache['lista'] as $item) {
            $data = isset($item['data']) ? $item['data'] : (isset($item['date']) ? $item['date'] : '');
            $nome = isset($item['nome']) ? $item['nome'] : (isset($item['name']) ? $item['name'] : '');
            $fer = pf_normalizar_feriado($data, $nome, null);
            if ($fer) {
                $listaNorm[] = $fer;
            }
        }
        return array(
            'lista' => $listaNorm,
            'fonte' => isset($cache['fonte']) ? $cache['fonte'] : 'Cache local',
            'atualizado_em' => isset($cache['atualizado_em']) ? $cache['atualizado_em'] : ''
        );
    }

    $remoto = pf_buscar_feriados_remotos($ano);
    pf_gravar_cache_feriados($ano, $remoto['lista'], $remoto['fonte']);

    $listaNorm = array();
    foreach ($remoto['lista'] as $item) {
        $fer = pf_normalizar_feriado($item['data'], $item['nome'], null);
        if ($fer) {
            $listaNorm[] = $fer;
        }
    }

    return array(
        'lista' => $listaNorm,
        'fonte' => $remoto['fonte'],
        'atualizado_em' => date('d/m/Y H:i')
    );
}

function pf_montar_feriados_post($post)
{
    $lista = array();
    if (empty($post['fer_data']) || !is_array($post['fer_data'])) {
        return array(
            'lista' => array(),
            'fonte' => isset($post['fer_fonte']) ? $post['fer_fonte'] : '',
            'atualizado_em' => isset($post['fer_atualizado']) ? $post['fer_atualizado'] : ''
        );
    }
    foreach ($post['fer_data'] as $i => $dataBr) {
        $dt = pf_parse_br_date($dataBr);
        if (!$dt) {
            continue;
        }
        $nome = isset($post['fer_nome'][$i]) ? trim($post['fer_nome'][$i]) : '';
        if ($nome === '') {
            $nome = 'Feriado';
        }
        $alta = !empty($post['fer_alta'][$i]);
        $fer = pf_normalizar_feriado($dt->format('Y-m-d'), $nome, $alta);
        if ($fer) {
            $lista[] = $fer;
        }
    }
    return array(
        'lista' => $lista,
        'fonte' => isset($post['fer_fonte']) ? $post['fer_fonte'] : 'Editado nesta tela',
        'atualizado_em' => isset($post['fer_atualizado']) ? $post['fer_atualizado'] : ''
    );
}

function pf_datas_alta_feriados($feriados)
{
    $datas = array();
    if (!is_array($feriados)) {
        return $datas;
    }
    foreach ($feriados as $f) {
        if (empty($f['alta']) || empty($f['alta_ini']) || empty($f['alta_fim'])) {
            continue;
        }
        $ini = DateTime::createFromFormat('Y-m-d', $f['alta_ini']);
        $fim = DateTime::createFromFormat('Y-m-d', $f['alta_fim']);
        if (!$ini || !$fim) {
            continue;
        }
        $c = clone $ini;
        $guard = 0;
        while ($c <= $fim && $guard < 16) {
            $datas[$c->format('Y-m-d')] = true;
            $c->modify('+1 day');
            $guard++;
        }
    }
    return $datas;
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
function pf_processar_linhas($registros, $valorAlta, $valorBaixa, $mapa, $feriados = array())
{
    $linhas = array();
    $totais = array(
        'autorizacoes' => 0,
        'hospedes' => 0,
        'cobraveis' => 0,
        'receita' => 0.0,
        'por_mes' => array()
    );
    $datasAltaFer = pf_datas_alta_feriados($feriados);

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

        // Temporada pelo mês da ENTRADA; feriado emendado sobrepõe para alta
        $ymEntrada = $dtEntrada ? $dtEntrada->format('Y-m') : '';
        $temporada = $ymEntrada !== '' ? pf_temporada_do_mes($ymEntrada, $mapa, 'baixa') : 'baixa';
        if ($dtEntrada && isset($datasAltaFer[$dtEntrada->format('Y-m-d')])) {
            $temporada = 'alta';
        }
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
