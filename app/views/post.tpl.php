<?php
  ob_start();
?>

<link rel="stylesheet" href="assets/css/editor.min.css">
<script defer src="assets/js/editor.min.js"></script>
<script>
  window.onload = function() {
  const { Editor } = toastui;
  const { chart, codeSyntaxHighlight, colorSyntax, tableMergedCell, uml } = Editor.plugin;
  const chartOptions = {
    minWidth: 100,
    maxWidth: 600,
    minHeight: 100,
    maxHeight: 300
  };

  const editor = new Editor({
    el: document.querySelector('#editor'),
    previewStyle: 'vertical',
    height: '60vh',
    //theme: 'dark',
    language: 'ru',
    initialValue: <?php echo json_encode($post['content'] ?? '') ?>,
    plugins: [
      [chart, chartOptions],
      [codeSyntaxHighlight, { highlighter: Prism }],
      colorSyntax,
      tableMergedCell,
      uml
    ],
    hooks: {
        addImageBlobHook: function(blob, callback) {
            const formData = new FormData();
            formData.append('image', blob);

            fetch('upload', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(imageUrl => {
                callback(imageUrl, 'alt text');
            });

            return false;
        }
    }
  });

  document.querySelector('.form').addEventListener('submit', function(event) {
    document.querySelector('#editor-content').value = editor.getMarkdown();
  });
};
</script>

<style>
  #editor {
    margin-bottom: 20px;
  }
</style>

<?php
$inline_head = ob_get_clean();
ob_start();
?>




<?php require VIEWS . '/includes/header.php' ?>

<form class="form" method="post">
    <input class="form__input form__input--post" type="text" id="title" name="title" placeholder="Заголовок" value="<?php echo htmlspecialchars($post['title'] ?? '') ?>">
    <div id="editor"></div>
    <input type="hidden" id="editor-content" name="content">

    <?php if (isset($site['pages']) && !empty($site['pages'])): ?>
<div class="form__short">
      <label class="form__label form-label" for="page">Страница:</label>
      <select class="form__select form-select" id="page" name="page">
          <option value="">-- Нет --</option>
          <?php foreach (explode(',', htmlspecialchars($site['pages'])) as $page): ?>
              <option value="<?= htmlspecialchars($page) ?>" <?= isset($post['page']) && htmlspecialchars($post['page']) === htmlspecialchars($page) ? 'selected' : '' ?>><?= htmlspecialchars($page) ?></option>
          <?php endforeach; ?>
      </select>
</div>
<?php endif; ?>

    <?php if (!$edit_mode): ?>
    <button class="btn btn__default" type="submit" name="submit">Опубликовать</button>
    <button class="btn" type="submit" name="draft">Сохранить в черновик</button>
    <?php else: ?>
        <?php if ($post['is_draft']): ?>
            <button class="btn btn__default" type="submit" name="submit">Опубликовать</button>
        <?php endif; ?>
        <button class="btn" type="submit" name="submit">Сохранить</button>
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <button class="btn btn__delete" type="submit" name="delete">Удалить</button>
        <?php endif; ?>
    <?php endif; ?>
</form>


<?php require VIEWS . '/includes/footer.php' ?>

