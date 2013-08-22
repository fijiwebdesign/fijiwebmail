<?php
/**
 * Fiji Mail Server 
 *
 * @author    gabe@fijiwebdesign.com
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

 
namespace app\mail\view\widget;

/**
 * Generate HTML to display the adding of labels
 */
class addLabel extends emailTools
{
    /**
     * Retrieve the HTML
     */
    public function toHtml()
    {
        $html = '';
        foreach ($this->links as $i => $link) {
            $html .= '<li><a href="#" data-value="' . htmlentities($link->value) . '" class="addlabel-label" data-id="' . htmlentities($link->value) . '">' . htmlspecialchars($link->name) . '</a></li>';
        }
        
        $html = '<div class="btn-group" id="' . htmlentities($this->id) . '">
            <button class="btn" data-toggle="dropdown">' . $this->title . '</button>
            <button class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
            <ul class="dropdown-menu">
                ' . $html . '
                <li><a id="addlabel-create" href="#"><i class="awe-plus"></i>&nbsp;New Label</a></li>
            </ul>
        </div>';
        
        $html .= $this->getJS();
        
        return $html;
    }
    
    public function setLinks($links)
    {
        $this->links = $links;
    }
    
    public function getJS()
    {
        /**
         * @todo move JS to head
         */
        ob_start();
        ?>
<div class="modal hide fade" id="addlabel-modal">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3>Add Label</h3>
  </div>
  <div class="modal-body">
    <p id="addmodal-body">
        <form id="addlabel-form">
            <label>Label Name</label>
            <input name="flag" type="text" />
            <label>Label Color</label>
            <input id="addlabel-color" name="color" type="text" class="pick-a-color" />
        </form>
    </p>
  </div>
  <div class="modal-footer">
      <a href="#" id="addlabel-cancel" data-dismiss="modal" class="btn">Cancel</a>
    <a href="#" id="addlabel-save" data-dismiss="modal" class="btn btn-primary">Save</a>
  </div>
</div>

<script type="text/javascript" src="public/plugins/pickacolor/js/tinycolor-0.9.15.min.js"></script>
<script type="text/javascript" src="public/plugins/pickacolor/js/pick-a-color-1.1.7.min.js"></script>
<script type="text/javascript">
!function($) {
    $(function() {
        $('#addlabel-create').bind('click', function(event) {
            event.preventDefault();
            $('#addlabel-modal').modal('show');
        });
        $('#addlabel-save').bind('click', function() {
            var url = '?app=mail&page=message&view=addLabel' 
                + '&' + $('#addlabel-form').serialize()
                + '&folder=' + $('.mailbox').attr('data-folder') 
                + '&' + $('#mailbox-form').serialize();
            window.location = url;
        });
        $('#addlabel-color').pickAColor();
        
        $('#addlabel-modal').on('show', function() {
            setTimeout(function() {
                var offset = $('#addlabel-color').offset();
                $('.color-menu').css({
                    top: offset.top + 30 + 'px',
                    left: offset.left + 30 + 'px'
                });
            }, 500);
        });
        $('.addlabel-label').click(function(event) {
            event.preventDefault();
            var url = '?app=mail&page=message&view=addLabel' 
                + '&label=' + $(this).attr('data-value')
                + '&folder=' + $('.mailbox').attr('data-folder') 
                + '&' + $('#mailbox-form').serialize()
                + '&p=' + getUrlParam('p') + '&q=' + getUrlParam('q');
            window.location = url;
        });
        
    });
}(jQuery);

/**
 * @todo: Move to global and add func to build urls from current 
 */
function parseQueryString() {
    var query = (window.location.search || '?').substr(1),
        map   = {};
    query.replace(/([^&=]+)=?([^&]*)(?:&+|$)/g, function(match, key, value) {
        (map[key] = map[key] || []).push(value);
    });
    return map;
}
function getUrlParam(name, def) {
    var map = parseQueryString();
    return map[name] || def || '';
}

</script>
<link type="text/css" rel="stylesheet" href="public/plugins/pickacolor/css/pick-a-color-1.1.7.min.css" />
<style type="text/css">
    div.pick-a-color-markup .color-menu {
        position: fixed;
        top: 0;
    }
</style> 
    <?php
    return ob_get_clean();    
        
    }
    
}
