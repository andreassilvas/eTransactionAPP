<?php
// Valeurs par défaut
$btnText = $btnText ?? 'Click Me';
$btnType = $btnType ?? 'button';
$href = $href ?? '';
$btnBg = $btnBg ?? '#005F66';
$btnBorder = $btnBorder ?? '#005F66';
$btnHoverBg = $btnHoverBg ?? '#00738A';
$btnHoverBorder = $btnHoverBorder ?? '#00738A';

$btnTextColor = $btnTextColor ?? '#fff';
$btnHoverText = $btnHoverText ?? '#fff';
$extraClass = $extraClass ?? '';

// Classe unique pour éviter les conflits entre plusieurs boutons
$uniqueClass = 'custom-btn-' . bin2hex(random_bytes(5));
?>

<style>
    .<?= $uniqueClass ?> {
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

    .<?= $uniqueClass ?>:hover {
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
    <a href="<?= $href ?>" class="btn <?= $uniqueClass ?> <?= $extraClass ?>">
        <?= $btnText ?>
    </a>
<?php else: ?>
    <button type="<?= $btnType ?>" class="btn <?= $uniqueClass ?> <?= $extraClass ?>">
        <?= $btnText ?>
    </button>
<?php endif; ?>