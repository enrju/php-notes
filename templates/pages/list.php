<div>
    <section>
        <div class="message">
            <?php
            if (!empty($params['before'])) {
                $id = $params['id'];
                switch ($params['before']) {
                    case 'created':
                        echo "Notatka została utworzona pod id: $id !!!";
                        break;
                    case 'edited':
                        echo "Notatka o id: $id została zaktualizowana !!!";
                        break;
                    case 'deleted':
                        echo "Notatka o id: $id została skasowana !!!";
                        break;
                }
            }
            ?>
        </div>

        <div class="message">
            <?php
            if (!empty($params['error'])) {
                switch ($params['error']) {
                    case 'noteNotFound':
                        echo 'Notatka nie została znaleziona !!!';
                        break;
                    case 'missingNoteId':
                        echo 'Niepoprawny identyfikator notatki !!!';
                        break;
                }
            }
            ?>
        </div>

        <?php
        if ($params['sort']) {
            $by = $params['sort']['by'] ?? 'title';
            $order = $params['sort']['order'] ?? 'desc';
        }
        ?>

        <div>
            <form action="/" class="settings-form" method="GET">
                <div>
                    <div>Sortuj po: </div>
                    <label>Tytule: <input type="radio" name="sortby" value="title" <?php echo $by === 'title' ? 'checked' : '' ?>></label>
                    <label>Dacie: <input type="radio" name="sortby" value="created" <?php echo $by === 'created' ? 'checked' : '' ?>></label>
                </div>

                <div>
                    <div>Kierunek sortowania: </div>
                    <label>Rosnąco: <input type="radio" name="sortorder" value="asc" <?php echo $order === 'asc' ? 'checked' : '' ?>></label>
                    <label>Malejąco: <input type="radio" name="sortorder" value="desc" <?php echo $order === 'desc' ? 'checked' : '' ?>></label>
                </div>

                <input type="submit" value="Sortuj">
            </form>
        </div>

        <div class="tbl-header">
            <table cellpadding="0" cellspacing="0" border="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Tytuł</th>
                        <th>Data</th>
                        <th>Opcje</th>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="tbl-content">
            <table cellpadding="0" cellspacing="0" border="0">
                <tbody>
                    <?php foreach ($params['notes'] as $note) : ?>
                        <tr>
                            <td><?php echo $note['id'] ?></td>
                            <td><?php echo $note['title'] ?></td>
                            <td><?php echo $note['created'] ?></td>
                            <td>
                                <a href="/?action=show&id=<?php echo $note['id'] ?>">
                                    <button>Szczegóły</button>
                                </a>
                                <a href="/?action=delete&id=<?php echo $note['id'] ?>">
                                    <button>Usuń</button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>