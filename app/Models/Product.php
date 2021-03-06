<?php

class Product{
    public $MaSP;
    public $TenSP;
    public $HinhSP;
    public $GiaSP;
    public $NgayNhap;
    public $SoLuongTon;
    public $SoLuongBan;
    public $SoLuotXem;
    public $BiXoa;
    public $MaLoaiSP;
    public $MaHangSX;
    public $MoTa;

    public function FromJson($obj){
        $data = json_decode($obj,true);
        foreach($data as $key => $val)
        {
            if(property_exists(__CLASS__,$key))
            {
                $this->$key =  $val;
            }
        }
    }

    public function save(){
        if($this->MaSP == null):
            $sql = "INSERT INTO sanpham VALUES (null,'$this->TenSP','$this->HinhSP','$this->GiaSP'
            ,'$this->NgayNhap','$this->SoLuongTon','$this->SoLuongBan',
            '$this->SoLuotXem','$this->MoTa','$this->BiXoa','$this->MaLoaiSP','$this->MaHangSX')";
        else:
            $sql = "UPDATE sanpham SET TenSanPham = '$this->TenSP', HinhURL = '$this->HinhSP', GiaSanPham = '$this->GiaSP', NgayNhap = '$this->NgayNhap',
                    SoLuongTon = '$this->SoLuongTon', SoLuongBan = '$this->SoLuongBan', SoLuotXem = '$this->SoLuotXem', MoTa = '$this->MoTa', BiXoa = '$this->BiXoa',
                    MaLoaiSanPham = '$this->MaLoaiSP', MaHangSanXuat = '$this->MaHangSX' WHERE MaSanPham = '$this->MaSP'";
        endif;
        if(Provider::ExecuteNonQuery($sql))
            return true;
    }

    public function delete(){
       $sql = "DELETE FROM sanpham WHERE MaSanPham = $this->MaSP";
       if(Provider::ExecuteNonQuery($sql))
            return true;
    }

    public static function find($id){
        $sql = "SELECT * FROM sanpham WHERE MaSanPham = '$id' AND BiXoa = FALSE LIMIT 1";
        if($data = Provider::ExecuteQuery($sql)){
            $item = new Product;
            while($row = mysqli_fetch_array($data)){
                $item->MaSP = $row['MaSanPham'];
                $item->TenSP = $row['TenSanPham'];
                $item->GiaSP = $row['GiaSanPham'];
                $item->HinhSP = $row['HinhURL'];
                $item->MoTa = $row['MoTa'];
                $item->NgayNhap = $row['NgayNhap'];
                $item->SoLuongTon = $row['SoLuongTon'];
                $item->SoLuongBan = $row['SoLuongBan'];
                $item->SoLuotXem = $row['SoLuotXem'];
                $item->BiXoa = $row['BiXoa'];
                $item->MaLoaiSP = $row['MaLoaiSanPham'];
                $item->MaHangSX = $row['MaHangSanXuat'];
            }
            return $item;
        }
    }

    public static function findByName($name){
        $sql = "SELECT * FROM sanpham WHERE TenSanPham LIKE '%$name%' AND BiXoa = FALSE LIMIT 10";
        if($data = Provider::ExecuteQuery($sql))
            return self::convert($data);
    }

    public static function all($num = 0){
        $sl = $num !=0 ? "LIMIT $num": "";
        $sql = "SELECT SP.MaSanPham, SP.TenSanPham, SP.GiaSanPham, SP.HinhURL, SP.MoTa
                FROM sanpham SP ".$sl;
        if($data = Provider::ExecuteQuery($sql))
            return self::convert($data);
    }

    public static function news(){
        $sql = "SELECT SP.MaSanPham, SP.TenSanPham, SP.GiaSanPham, SP.HinhURL, SP.MoTa
                FROM sanpham SP
                WHERE SP.BiXoa = FALSE
                ORDER BY SP.NgayNhap DESC LIMIT 0, 8";
        if($data = Provider::ExecuteQuery($sql))
            return self::convert($data);
    }

    public static function mostBuy(){
        $sql = "SELECT SP.MaSanPham, SP.TenSanPham, SP.GiaSanPham, SP.HinhURL, SP.MoTa
                FROM sanpham SP
                WHERE SP.BiXoa = FALSE
                ORDER BY SP.SoLuongBan DESC LIMIT 0, 8";
        if($data = Provider::ExecuteQuery($sql))
            return self::convert($data);
    }

    public static function mostView(){
        $sql = "SELECT SP.MaSanPham, SP.TenSanPham, SP.GiaSanPham, SP.HinhURL, SP.MoTa
                FROM sanpham SP
                WHERE SP.BiXoa = FALSE
                ORDER BY SP.SoLuotXem DESC LIMIT 0, 8";
        if($data = Provider::ExecuteQuery($sql))
            return self::convert($data);
    }

    public static function getByType($id){
        $sql = "SELECT SP.MaSanPham, SP.TenSanPham, SP.GiaSanPham, SP.HinhURL, SP.MoTa
                FROM sanpham SP
                WHERE SP.MaLoaiSanPham = $id AND SP.BiXoa = FALSE
                ORDER BY SP.SoLuongBan DESC LIMIT 0, 8";
        if($data = Provider::ExecuteQuery($sql))
            return self::convert($data);
    }

    public static function getByFactory($id){
        $sql = "SELECT SP.MaSanPham, SP.TenSanPham, SP.GiaSanPham, SP.HinhURL, SP.MoTa
                FROM sanpham SP
                WHERE SP.MaHangSanXuat = $id AND SP.BiXoa = FALSE
                ORDER BY SP.SoLuongBan DESC LIMIT 0, 8";
        if($data = Provider::ExecuteQuery($sql))
            return self::convert($data);
    }

    public static function search($prices,$types){
        $priceString = '';
        $typeString = '';
        if($prices)
            $priceString = 'SP.GiaSanPham IN('.implode(',', $prices).')';
        if($types)
            $typeString = 'SP.MaLoaiSanPham IN('.implode(',', $types).')';
        $whereCondition = '';
        if($typeString != '' && $priceString != '')
            $whereCondition = "WHERE $priceString AND $typeString";
        else if($typeString == '' && $priceString != '')
            $whereCondition = "WHERE $priceString";
        else 
            $whereCondition = "WHERE $typeString";
        $sql = "SELECT SP.MaSanPham, SP.TenSanPham, SP.GiaSanPham, SP.HinhURL, SP.MoTa
        FROM sanpham SP
        $whereCondition
        ORDER BY SP.SoLuongBan DESC LIMIT 0, 8";

        if($data = Provider::ExecuteQuery($sql))
            return self::convert($data);
    }

    public static function more($id){
        $sql = "SELECT SP.MaSanPham, SP.TenSanPham, SP.GiaSanPham, SP.HinhURL, SP.MoTa
        FROM sanpham SP
        WHERE SP.MaSanPham > $id AND SP.BiXoa = FALSE
        LIMIT 0, 8";
        if($data = Provider::ExecuteQuery($sql))
            return self::convert($data);
    }

    static function convert($data){
        $result = array();
        while($row = mysqli_fetch_array($data)){
            $item = new Product;
            $item->MaSP = $row['MaSanPham'];
            $item->TenSP = $row['TenSanPham'];
            $item->GiaSP = $row['GiaSanPham'];
            $item->HinhSP = $row['HinhURL'];
            $item->MoTa = $row['MoTa'];
            $result[] = $item;
        }
        return $result;
    }

}