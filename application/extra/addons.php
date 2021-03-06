<?php

return array (
  'autoload' => false,
  'hooks' => 
  array (
    'app_init' => 
    array (
      0 => 'alioss',
      1 => 'epay',
    ),
    'upload_config_init' => 
    array (
      0 => 'alioss',
    ),
    'upload_delete' => 
    array (
      0 => 'alioss',
    ),
    'sms_send' => 
    array (
      0 => 'clsms',
    ),
    'sms_notice' => 
    array (
      0 => 'clsms',
    ),
    'sms_check' => 
    array (
      0 => 'clsms',
    ),
    'ems_send' => 
    array (
      0 => 'faems',
    ),
    'ems_notice' => 
    array (
      0 => 'faems',
    ),
    'leesignhook' => 
    array (
      0 => 'leesign',
    ),
    'admin_login_init' => 
    array (
      0 => 'loginbg',
    ),
    'response_send' => 
    array (
      0 => 'loginvideo',
    ),
    'upload_after' => 
    array (
      0 => 'thumb',
    ),
    'wipecache_after' => 
    array (
      0 => 'tinymce',
    ),
    'set_tinymce' => 
    array (
      0 => 'tinymce',
    ),
  ),
  'route' => 
  array (
    '/example$' => 'example/index/index',
    '/example/d/[:name]' => 'example/demo/index',
    '/example/d1/[:name]' => 'example/demo/demo1',
    '/example/d2/[:name]' => 'example/demo/demo2',
    '/leesign$' => 'leesign/index/index',
    '/qrcode$' => 'qrcode/index/index',
    '/qrcode/build$' => 'qrcode/index/build',
    '/third$' => 'third/index/index',
    '/third/connect/[:platform]' => 'third/index/connect',
    '/third/callback/[:platform]' => 'third/index/callback',
  ),
  'priority' => 
  array (
  ),
);