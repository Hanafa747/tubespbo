<?php showFlash(); ?>
<div class="admin-welcome"><div><div class="eyebrow">PENGELOLAAN DATA</div><h1><?= e($cfg['title']) ?></h1><p>Tambah, lihat, ubah, dan hapus data CineTix.</p></div></div>
<section class="admin-panel form-panel">
    <div class="panel-heading"><div><div class="eyebrow"><?= $editRow ? 'PERBARUI DATA' : 'DATA BARU' ?></div><h2><?= $editRow ? 'Ubah ' : 'Tambah ' ?><?= e(strtolower($cfg['title'])) ?></h2></div></div>
    <form class="crud-form" method="post" enctype="multipart/form-data" action="index.php?page=save&entity=<?= e($entity) ?>">
        <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
        <input type="hidden" name="id" value="<?= (int)($editRow[$cfg['pk']] ?? 0) ?>">
        <div class="crud-fields">
        <?php foreach ($cfg['fields'] as $name => $field): $value = $editRow[$name] ?? ''; ?>
            <label class="form-field <?= $field['type'] === 'textarea' ? 'field-wide' : '' ?>">
                <span><?= e($field['label']) ?><?= !empty($field['required']) ? ' *' : '' ?></span>
                <?php if ($field['type'] === 'select'): ?>
                    <select name="<?= e($name) ?>" <?= !empty($field['required']) ? 'required' : '' ?>>
                        <option value="">Pilih <?= e(strtolower($field['label'])) ?></option>
                        <?php foreach (($selectOptions[$field['table']] ?? []) as $option): ?>
                            <option value="<?= e($option[$field['key']]) ?>" <?= (string)$value === (string)$option[$field['key']] ? 'selected' : '' ?>><?= e($option[$field['display']]) ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php elseif ($field['type'] === 'textarea'): ?>
                    <textarea name="<?= e($name) ?>" <?= !empty($field['required']) ? 'required' : '' ?>><?= e($value) ?></textarea>
                <?php elseif ($field['type'] === 'file'): ?>
                    <input type="file" name="<?= e($name) ?>" accept="image/jpeg,image/png,image/webp">
                    <?php if ($value): ?><small class="field-help">Poster saat ini: <a href="<?= e($value) ?>" target="_blank">Lihat gambar</a>. Kosongkan jika tidak ingin mengganti.</small><?php endif; ?>
                <?php else: ?>
                    <input type="<?= e($field['type']) ?>" name="<?= e($name) ?>" value="<?= $field['type'] === 'password' ? '' : e($value) ?>" <?= !empty($field['required']) && !($field['type'] === 'password' && $editRow) ? 'required' : '' ?> <?= $field['type'] === 'number' ? 'min="0" step="any"' : '' ?> placeholder="<?= $field['type'] === 'password' && $editRow ? 'Kosongkan jika tidak diubah' : '' ?>">
                    <?php if ($field['type'] === 'password'): ?><small class="field-help">Password akan disimpan dalam bentuk hash.</small><?php endif; ?>
                <?php endif; ?>
            </label>
        <?php endforeach; ?>
        </div>
        <div class="form-actions"><button class="button-primary" type="submit"><?= $editRow ? 'Simpan perubahan' : 'Simpan data' ?></button><?php if ($editRow): ?><a class="button-quiet" href="index.php?page=manage&entity=<?= e($entity) ?>">Batal</a><?php endif; ?></div>
    </form>
</section>

<section class="admin-panel">
    <div class="panel-heading"><div><div class="eyebrow">DATABASE</div><h2>Daftar <?= e($cfg['title']) ?></h2></div><span class="record-count"><?= count($rows) ?> data</span></div>
    <div class="table-scroll"><table><thead><tr><?php foreach ($cfg['columns'] as $column): ?><th><?= e(str_replace('_',' ',$column)) ?></th><?php endforeach; ?><th>Aksi</th></tr></thead><tbody>
    <?php foreach ($rows as $row): ?><tr>
        <?php foreach ($cfg['columns'] as $column): ?>
        <td>
            <?php if ($column === 'sampul' && !empty($row[$column])): ?><img class="table-poster" src="<?= e($row[$column]) ?>" alt="poster">
            <?php elseif ($column === 'password'): ?>••••••••
            <?php elseif ($column === 'total_harga' || $column === 'harga_tiket'): ?>Rp <?= number_format((float)$row[$column],0,',','.') ?>
            <?php elseif ($column === 'sinopsis' || $column === 'alamat'): ?><?= e(mb_strimwidth((string)$row[$column],0,48,'…')) ?>
            <?php else: ?><?= e($row[$column] ?? '-') ?><?php endif; ?>
        </td>
        <?php endforeach; ?>
        <td class="row-actions"><a href="index.php?page=manage&entity=<?= e($entity) ?>&edit=<?= (int)$row[$cfg['pk']] ?>">Edit</a><a class="delete-link" href="index.php?page=delete&entity=<?= e($entity) ?>&id=<?= (int)$row[$cfg['pk']] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a></td>
    </tr><?php endforeach; ?>
    <?php if (!$rows): ?><tr><td colspan="<?= count($cfg['columns']) + 1 ?>" class="table-empty">Belum ada data.</td></tr><?php endif; ?>
    </tbody></table></div>
</section>
