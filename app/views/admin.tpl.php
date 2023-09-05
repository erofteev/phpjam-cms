<?php require VIEWS . '/includes/header.php' ?>



<form class="mb-m" method="post">
  <h2>Публикации</h2>
  <?php if ($result->num_rows > 0): ?>
    <ul class="list publications">
      <?php while ($row = $result->fetch_assoc()): ?>
        <li class="list__item">
          <?php if ($_SESSION['role'] === 'admin'): ?>
            <input class="list__check" type="checkbox" name="ids[]" value="<?php echo $row['id'] ?>">
          <?php endif; ?>

          <div class="list__row">
            <a class="link list__link" href="post?id=<?php echo $row['id'] ?>">
              <?php echo htmlspecialchars($row['title']) ?>
            </a>
          </div>
          <?php if ($row['create_date'] == $row['update_date']): ?>
            <span class="list__date" title="Создано: <?php echo date('d.m.Y - H:i', strtotime($row['create_date'])) ?>"><?php echo date('d.m.Y', strtotime($row['create_date'])) ?></span>
          <?php else: ?>
            <span class="list__date" title="Изменено: <?php echo date('d.m.Y - H:i', strtotime($row['update_date'])) ?>"><?php echo date('d.m.Y', strtotime($row['create_date'])) ?></span>
          <?php endif; ?>
          <?php if (isset($row['page'])): ?>
            <span class="list__page"><?php echo htmlspecialchars($row['page']) ?></span>
          <?php endif; ?>
        </li>
      <?php endwhile; ?>
    </ul>
    <ul class="pagination">
  <?php for ($i = 1; $i <= $total_pages; $i++): ?>
    <li class="pagination__item"><a class="pagination__link" href="admin?page=<?php echo $i ?>&drafts_page=<?php echo $drafts_page ?>"><?php echo $i ?></a></li>
  <?php endfor; ?>
</ul>
  <?php else: ?>
    <ul class="list">
      <li class="list__empty">Публикаций нет</li>
    </ul>
  <?php endif; ?>

  <h2>Черновики</h2>
  <?php if ($draftsResult->num_rows > 0): ?>
    <ul class="list">
      <?php while ($row = $draftsResult->fetch_assoc()): ?>
        <li class="list__item">
          <?php if ($_SESSION['role'] === 'admin'): ?>
            <input class="list__check" type="checkbox" name="ids[]" value="<?php echo $row['id'] ?>">
          <?php endif; ?>

          <div class="list__row">
          <a class="link list__link" href="post?id=<?php echo $row['id'] ?>">
            <?php echo htmlspecialchars($row['title']) ?>
          </a>
          </div>
          <?php if (isset($row['page'])): ?>
            <span class="list__page"><?php echo htmlspecialchars($row['page']) ?></span>
          <?php endif; ?>
        </li>
      <?php endwhile; ?>
    </ul>
    <ul class="pagination">
  <?php for ($i = 1; $i <= $total_drafts_pages; $i++): ?>
    <li class="pagination__item"><a class="pagination__link" href="admin?page=<?php echo $page ?>&drafts_page=<?php echo $i ?>"><?php echo $i ?></a></li>
  <?php endfor; ?>
</ul>
  <?php else: ?>
    <ul class="list">
      <li class="list__empty">Черновиков нет</li>
    </ul>
  <?php endif; ?>

  <?php if ($_SESSION['role'] === 'admin'): ?>
    <button class="btn btn--s" id="btn-draft" type="submit" name="draft" disabled>Перенести в черновик</button>
    <button class="btn btn--s btn__delete" id="btn-delete" type="submit" name="delete" disabled>Удалить отмеченные</button>
  <?php endif; ?>
</form>

<a class="btn btn__default" href="post">Создать новую публикацию</a>

<?php require VIEWS . '/includes/footer.php' ?>