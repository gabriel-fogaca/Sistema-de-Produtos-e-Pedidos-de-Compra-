<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <h1 class="h2">Logs de Alterações</h1>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>ID do Usuário</th>
                <th>Ação</th>
                <th>Descrição</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
            <tr>
                <td><?= $log['id'] ?></td>
                <td><?= $log['user_id'] ?></td>
                <td><?= $log['action'] ?></td>
                <td><?= $log['description'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>