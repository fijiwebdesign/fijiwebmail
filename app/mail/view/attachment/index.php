<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */
 
$SearchWidget = '';
?>

<article class="data-block">
    <div class="data-container">
        <header class="messages-title">
            <h2><?php echo htmlentities($header, ENT_QUOTES, 'UTF-8'); ?></h2>
            <?php echo $SearchWidget; ?>
        </header>
        <section>
            <pre>
            <?php var_dump($AttachmentList); ?>
            </pre>
        </section>
            
    </div>
</article>
