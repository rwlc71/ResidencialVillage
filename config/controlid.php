<?php
/**
 * Integração com IDCONTROL / iDSecure na máquina do condomínio.
 *
 * 45.71.177.247 = IPv4 público dessa máquina (MyIPAddress.com).
 * Não existe pasta /ResidencialVillage nesse IP.
 * A API do IDCONTROL/iDSecure é https://45.71.177.247:30443
 *
 * Este WAMP (localhost) conecta DIRETO nesse endereço.
 * No roteador do condomínio a porta 30443 TCP precisa apontar para o PC do IDCONTROL.
 */

return array(
    'tipo' => 'idsecure',
    'destino' => 'remoto',

    'host_publico' => '45.71.177.247',
    'host_local' => '127.0.0.1',
    'host_lan' => '',

    'port' => 30443,
    'use_https' => true,

    'login' => 'admin',
    'password' => 'admin',

    'modo' => 'standalone',
    'timeout' => 30,
    'registration_prefix' => '',
    'default_group_id' => 1,
    'auto_sync_proprietario' => true,
    'callback_base_url' => '',
);
