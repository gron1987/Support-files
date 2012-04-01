<?php
/**
 * @var $userFrom \Auth\User
 * @var $userTo \Auth\User
 * @var $item array
 */
?>
<div class="<?= ($item['private'] == 1) ? 'private' : '';  ?>">
<span class="time"><?= date('M.d H:i:s', floor($item['time']/10000)); ?></span>
<span class="from">
    <a href="javascript:toUser('<?= $userFrom->getId() ?>','<?= $userFrom->getLogin() ?>');">
        <?= $userFrom->getLogin() ?>
    </a>
</span>
<? if ($userTo != null) { ?>
->
<span class="to">
    <a href="javascript:toUser('<?= $userTo->getId() ?>','<?= $userTo->getLogin() ?>');">
        <?= $userTo->getLogin() ?>
    </a>
</span>
<? } else { ?>
:
<? } ?>
<span class="message"><?= smiles($item['message']) ?></span>
</div>