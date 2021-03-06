<?php

namespace MailPoet\Newsletter\Renderer\Blocks;

if (!defined('ABSPATH')) exit;


use MailPoet\Newsletter\Renderer\EscapeHelper as EHelper;

class Spacer {
  public static function render($element) {
    $height = (int)$element['styles']['block']['height'];
    $backgroundColor = EHelper::escapeHtmlAttr($element['styles']['block']['backgroundColor']);
    $template = '
      <tr>
        <td class="mailpoet_spacer" ' .
      (($backgroundColor !== 'transparent') ? 'bgcolor="' . $backgroundColor . '" ' : '') .
      'height="' . $height . '" valign="top"></td>
      </tr>';
    return $template;
  }
}
