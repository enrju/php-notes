<div class="show">
    <?php $note = $params['note'] ?? null ?>
    <?php if ($note) : ?>
        <ul>
            <li>Id: <?php echo $note['id'] ?></li>
            <li>Tytuł: <?php echo $note['title'] ?></li>
            <li>Opis: <?php echo $note['description'] ?></li>
            <li>Utworzono: <?php echo $note['created'] ?></li>
        </ul>

        <form action="/?action=delete" method="POST">
            <input type="hidden" name="id" value="<?php echo $note['id'] ?>">
            <input type="submit" value="Usuń">
        </form>
    <?php else : ?>
        <div>Brak notatki do wyświetlenia</div>
    <?php endif; ?>
    <a href="/">
        <button>Powrót do listy notatek</button>
    </a>
</div>