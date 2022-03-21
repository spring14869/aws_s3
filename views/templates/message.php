<? if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success" role="alert">
        <?= $_SESSION['success'] ?>
    </div>
<?
    unset($_SESSION['success']);
endif; ?>

<? if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?= $_SESSION['error'] ?>
    </div>
<?
    unset($_SESSION['error']);
endif; ?>