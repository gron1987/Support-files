<span><?= $item['login'] ?></span>
<a href="javascript:void(0)" onclick="toUser('<?= $item['id'] ?>','<?= $item['login'] ?>');$('#message_type').val(0);">To user</a>
<a href="javascript:void(0)" onclick="toUser('<?= $item['id'] ?>','<?= $item['login'] ?>');$('#message_type').val(1);">Private</a>
<br />