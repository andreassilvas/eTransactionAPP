<?php
// Default values
$btnText = $btnText ?? 'Click Me';
$btnType = $btnType ?? 'button';
$href = $href ?? '';
$btnBg = $btnBg ?? '#0047AB';
$btnBorder = $btnBorder ?? '#0047AB';
$btnTextColor = $btnTextColor ?? '#fff';
$btnHoverBg = $btnHoverBg ?? '#002F6C';
$btnHoverBorder = $btnHoverBorder ?? '#002F6C';
$btnHoverText = $btnHoverText ?? '#fff';
$extraClass = $extraClass ?? '';
?>

<style>
    .custom-btn {
        background-color:
            <?= $btnBg ?>
        ;
        border: 2px solid
            <?= $btnBorder ?>
        ;
        color:
            <?= $btnTextColor ?>
        ;
        border-radius: 5px;
        padding: 10px 20px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }

    .custom-btn:hover {
        background-color:
            <?= $btnHoverBg ?>
        ;
        border-color:
            <?= $btnHoverBorder ?>
        ;
        color:
            <?= $btnHoverText ?>
        ;
    }
</style>

<?php if (!empty($href)): ?>
    <a href="<?= $href ?>" class="btn custom-btn <?= $extraClass ?>"><?= $btnText ?></a>
<?php else: ?>
    <button type="<?= $btnType ?>" class="btn custom-btn <?= $extraClass ?>"><?= $btnText ?></button>
<?php endif; ?>