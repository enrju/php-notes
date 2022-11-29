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
            $sortBy = $params['sort']['by'] ?? 'title';
            $sortOrder = $params['sort']['order'] ?? 'desc';
        }

        if ($params['page']) {
            $pageSize = $params['page']['size'] ?? 10;
            $pageNumber = $params['page']['number'] ?? 1;
            $pagePages = $params['page']['pages'] ?? 1;
        }

        $phrase = $params['phrase'] ?? null;
        ?>

        <div>
            <form action="/" class="settings-form" method="GET">
                <div>
                    <label>Wyszukaj: <input type="text" name="phrase" value="<?php echo $phrase ?>"></label>
                </div>

                <div>
                    <div>Sortuj po: </div>
                    <label>Tytule: <input type="radio" name="sortby" value="title" <?php echo $sortBy === 'title' ? 'checked' : '' ?>></label>
                    <label>Dacie: <input type="radio" name="sortby" value="created" <?php echo $sortBy === 'created' ? 'checked' : '' ?>></label>
                </div>

                <div>
                    <div>Kierunek sortowania: </div>
                    <label>Rosnąco: <input type="radio" name="sortorder" value="asc" <?php echo $sortOrder === 'asc' ? 'checked' : '' ?>></label>
                    <label>Malejąco: <input type="radio" name="sortorder" value="desc" <?php echo $sortOrder === 'desc' ? 'checked' : '' ?>></label>
                </div>

                <div>
                    <div>Ile wyświetlić na stronę:</div>
                    <label>1 <input type="radio" name="pagesize" value="1" <?php echo $pageSize === 1 ? 'checked' : '' ?>></label>
                    <label>5 <input type="radio" name="pagesize" value="5" <?php echo $pageSize === 5 ? 'checked' : '' ?>></label>
                    <label>10 <input type="radio" name="pagesize" value="10" <?php echo $pageSize === 10 ? 'checked' : '' ?>></label>
                    <label>25 <input type="radio" name="pagesize" value="25" <?php echo $pageSize === 25 ? 'checked' : '' ?>></label>
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

        <?php
        $paginationUrl = "/?phrase=$phrase&sortby=$sortBy&sortorder=$sortOrder&pagesize=$pageSize&pagenumber=";
        ?>

        <ul class="pagination">
            <?php if ($pageNumber !== 1) : ?>
                <li>
                    <a href="<?php echo $paginationUrl . ($pageNumber - 1) ?>">
                        <button>&lt&lt</button>
                    </a>
                </li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $pagePages; $i++) : ?>
                <?php if ($i === $pageNumber) : ?>
                    <li class="current-page">
                    <?php else : ?>
                    <li>
                    <?php endif; ?>
                    <a href="<?php echo $paginationUrl . $i ?>">
                        <button><?php echo $i ?></button>
                    </a>
                    </li>
                <?php endfor; ?>
                <?php if ($pageNumber < $pagePages) : ?>
                    <li>
                        <a href="<?php echo $paginationUrl . ($pageNumber + 1) ?>">
                            <button>>></button>
                        </a>
                    </li>
                <?php endif; ?>
        </ul>
    </section>
</div>