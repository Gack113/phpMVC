<div class="container">
    <div style="margin-top: 5%; text-align:center">
        <h1>Quản Lí Loại Tài Khoản</h1>
    </div>
    <div style="margin: 1% 0; text-align:right">
        <?php include('add.php') ?>
    </div>
    <table class="table table-striped table-sm">
        <thead>
            <tr>
            <th>#</th>
            <th>Tên</th>
            <th style="text-align:right">Thao Tác</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach(UserType::all() as $item):?>
            <tr>
                <th scope="row"><?= $item->MaLoaiTaiKhoan ?></th>
                <td><?= $item->TenLoaiTaiKhoan ?></td>
                <td style="text-align:right"><a href="#"><button class="btn btn-warning">Sửa</button></a>&nbsp;<a href="#"><button class="btn btn-danger">Xóa</button></a></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
