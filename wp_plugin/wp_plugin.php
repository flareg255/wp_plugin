<?php
/*
Plugin Name: test plugin
Plugin URI: 
Description: テストです
Version: 1.0.0
Author:geregere
Author URI: http://example.com
License: GPL2
Copyright 2020 geregere (email : info@ultrazone.blue)
 
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) exit;

add_action('init', 'WpPlugin::init');

class WpPlugin{

    const VERSION           = '1.0.0';
    const PLUGIN_ID         = 'wp-plugin';
    const CREDENTIAL_ACTION = self::PLUGIN_ID . '-nonce-action';
    const CREDENTIAL_NAME   = self::PLUGIN_ID . '-nonce-key';
    const PLUGIN_DB_PREFIX  = self::PLUGIN_ID . '_';

    static function init() {
        return new self();
    }

    function __construct() {
        if (is_admin() && is_user_logged_in()) {
            add_action('admin_menu', [$this, 'set_plugin_menu']);
            add_action('admin_menu', [$this, 'set_plugin_sub_menu']);
        }
    }

    function set_plugin_menu() {
        add_menu_page(
            'カスタムバナー',           /* ページタイトル*/
            'カスタムバナー',           /* メニュータイトル */
            'manage_options',         /* 権限 */
            'wp-plugin',    /* ページを開いたときのURL */
            [$this, 'show_about_plugin'],       /* メニューに紐づく画面を描画するcallback関数 */
            'dashicons-format-gallery', /* アイコン see: https://developer.wordpress.org/resource/dashicons/#awards */
            99                          /* 表示位置のオフセット */
        );
    }

    function set_plugin_sub_menu() {
        add_submenu_page(
            'wp-plugin',  /* 親メニューのslug */
            '設定',
            '設定',
            'manage_options',
            'wp-plugin-config',
            [$this, 'show_config_form']
        );
    }

    function show_about_plugin() {
        $html = "<h1>カスタムバナー</h1>";
        $html .= "<p>トップページに表示するバナーを指定できます</p>";

        echo $html;
    }

    function show_config_form() {
        // ① wp_optionsのデータをひっぱってくる
        $title = get_option(self::PLUGIN_DB_PREFIX . "_title");
?>
        <div class="wrap">
            <h1>カスタムバナーの設定</h1>

            <form action="" method='post' id="my-submenu-form">
                <?php // ②：nonceの設定 ?>
                <?php wp_nonce_field(self::CREDENTIAL_ACTION, self::CREDENTIAL_NAME) ?>

                <p>
                <label for="title">タイトル：</label>
                <input type="text" name="title" value="<?= $title ?>"/>
                </p>

                <p><input type='submit' value='保存' class='button button-primary button-large'></p>
            </form>
        </div>
<?php
    }
}
?>