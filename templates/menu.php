<ul class="main-menu">
    <?php foreach ($menu as $value): ?>
        <li class="item-list <?=(isCurrentUrl($value['path'])) ? "menu-item-active" : ''?>"><a href="<?=$value['path'] ?>"><?=cutString ($value['title'])?></a></li>
    <?php endforeach ?>
</ul>
