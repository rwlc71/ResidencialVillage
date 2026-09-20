<?php
/**
 * Hooks de sincronização automática Control iD.
 * Falhas de rede/equipamento não interrompem o cadastro local.
 */

function controlid_auto_sync_proprietario($idProprietario, $usuario = '', $con = null)
{
    try {
        $cfgPath = dirname(__FILE__) . '/../config/controlid.php';
        if (!file_exists($cfgPath)) {
            return;
        }
        $cfg = include $cfgPath;
        if (empty($cfg['auto_sync_proprietario'])) {
            return;
        }
        require_once dirname(__FILE__) . '/ControlIdSyncService.php';
        if ($con === null) {
            if (isset($GLOBALS['con'])) {
                $con = $GLOBALS['con'];
            }
        }
        // mysql_* aceita link nulo e usa a conexão padrão ativa
        $service = new ControlIdSyncService($con, $cfg);
        $service->syncProprietario((int) $idProprietario, $usuario);
    } catch (Exception $e) {
        // silencioso
    }
}

function controlid_auto_sync_dependente($idDependente, $usuario = '', $con = null)
{
    try {
        $cfgPath = dirname(__FILE__) . '/../config/controlid.php';
        if (!file_exists($cfgPath)) {
            return;
        }
        $cfg = include $cfgPath;
        if (empty($cfg['auto_sync_proprietario'])) {
            return;
        }
        require_once dirname(__FILE__) . '/ControlIdSyncService.php';
        if ($con === null && isset($GLOBALS['con'])) {
            $con = $GLOBALS['con'];
        }
        $service = new ControlIdSyncService($con, $cfg);
        $service->syncDependente((int) $idDependente, $usuario);
    } catch (Exception $e) {
        // silencioso
    }
}
