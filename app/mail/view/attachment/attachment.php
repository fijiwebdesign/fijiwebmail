<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

$header = 'Attachment';
?>

<article class="data-block">
    <div class="data-container">
        <header class="messages-title">
            <h2><?php echo htmlentities($header, ENT_QUOTES, 'UTF-8'); ?></h2>
        </header>
        <section>
            <pre>
            <?php var_dump($Attachment); ?>
            </pre>
        </section>
            
    </div>
</article>
