CREATE TABLE b02_ship_particular (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_kode VARCHAR(255) NOT NULL UNIQUE,
    nama_kpl VARCHAR(255) NOT NULL,
    ship_particular_ket TEXT NULL,
    file_dok VARCHAR(255) NULL,
    created_by VARCHAR(255) NULL,
    updated_by VARCHAR(255) NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    INDEX idx_nama_kpl (nama_kpl),
    INDEX idx_created_by (created_by),
    INDEX idx_updated_by (updated_by),

    CONSTRAINT fk_b02_nama_kpl
        FOREIGN KEY (nama_kpl) REFERENCES a05_dm_kapal(id_kode)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_b02_created_by
        FOREIGN KEY (created_by) REFERENCES a01_dm_users(id_kode)
        ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT fk_b02_updated_by
        FOREIGN KEY (updated_by) REFERENCES a01_dm_users(id_kode)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- KATEGORI BARANG
CREATE TABLE a08_dm_kategori_brg (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_kode VARCHAR(255) NOT NULL UNIQUE,
    kategori_brg VARCHAR(255) NOT NULL,
    ket_brg TEXT NULL,
    created_by VARCHAR(255) NULL,
    updated_by VARCHAR(255) NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    INDEX idx_created_by (created_by),
    INDEX idx_updated_by (updated_by),

    CONSTRAINT fk_a08_created_by
        FOREIGN KEY (created_by) REFERENCES a01_dm_users(id_kode)
        ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT fk_a08_updated_by
        FOREIGN KEY (updated_by) REFERENCES a01_dm_users(id_kode)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- JENIS BARANG
CREATE TABLE a09_dm_jenis_brg (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_kode VARCHAR(255) NOT NULL UNIQUE,
    jenis_brg VARCHAR(255) NOT NULL,
    ket_brg TEXT NULL,
    created_by VARCHAR(255) NULL,
    updated_by VARCHAR(255) NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    INDEX idx_created_by (created_by),
    INDEX idx_updated_by (updated_by),

    CONSTRAINT fk_a09_created_by
        FOREIGN KEY (created_by) REFERENCES a01_dm_users(id_kode)
        ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT fk_a09_updated_by
        FOREIGN KEY (updated_by) REFERENCES a01_dm_users(id_kode)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- GOLONGAN BARANG
CREATE TABLE a10_dm_golongan_brg (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_kode VARCHAR(255) NOT NULL UNIQUE,
    golongan_brg VARCHAR(255) NOT NULL,
    ket_brg TEXT NULL,
    created_by VARCHAR(255) NULL,
    updated_by VARCHAR(255) NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    INDEX idx_created_by (created_by),
    INDEX idx_updated_by (updated_by),

    CONSTRAINT fk_a10_created_by
        FOREIGN KEY (created_by) REFERENCES a01_dm_users(id_kode)
        ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT fk_a10_updated_by
        FOREIGN KEY (updated_by) REFERENCES a01_dm_users(id_kode)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- NAMA BARANG
CREATE TABLE a11_dm_nama_brg (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_kode VARCHAR(255) NOT NULL UNIQUE,
    no_kode_brg VARCHAR(255) NOT NULL UNIQUE,
    id_kode_a08 VARCHAR(255) NOT NULL,
    id_kode_a09 VARCHAR(255) NOT NULL,
    id_kode_a10 VARCHAR(255) NOT NULL,
    nama_brg VARCHAR(255) NOT NULL,
    keterangan_brg TEXT(255),
    created_by VARCHAR(255) NULL,
    updated_by VARCHAR(255) NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    INDEX idx_id_kode_a08 (id_kode_a08),
    INDEX idx_id_kode_a09 (id_kode_a09),
    INDEX idx_id_kode_a10 (id_kode_a10),
    INDEX idx_created_by (created_by),
    INDEX idx_updated_by (updated_by),

    CONSTRAINT fk_a11_kategori_brg
        FOREIGN KEY (id_kode_a08) REFERENCES a08_dm_kategori_brg(id_kode)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_a11_jenis_brg
    	FOREIGN KEY (id_kode_a09) REFERENCES a09_dm_jenis_brg(id_kode)
    	ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_a11_golongan_brg
    	FOREIGN KEY (id_kode_a10) REFERENCES a10_dm_golongan_brg(id_kode)
    	ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_a11_created_by
        FOREIGN KEY (created_by) REFERENCES a01_dm_users(id_kode)
        ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT fk_a11_updated_by
        FOREIGN KEY (updated_by) REFERENCES a01_dm_users(id_kode)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- INVENTARIS
CREATE TABLE b03_Inventaris_Kpl (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_kode VARCHAR(255) NOT NULL UNIQUE,
    id_kode_a05 VARCHAR (255) NOT NULL,
    id_kode_a11 VARCHAR (255) NOT NULL,
    id_kode_a08 VARCHAR (255) NOT NULL,
    id_kode_a09 VARCHAR (255) NOT NULL,
    id_kode_a10 VARCHAR (255) NOT NULL,
    no_kode_brg INT (100) NOT NULL UNIQUE,
    no_kode_brg_subtitusi INT (100) NOT NULL UNIQUE,
    tipe_brg VARCHAR (100) NOT NULL,
    spesifikasi_brg VARCHAR (100) NOT NULL,
    satuan_brg VARCHAR (100) NOT NULL,
    merek_brg VARCHAR (100) NOT NULL,
    supplier_brg VARCHAR (100) NOT NULL,
    lokasi_brg VARCHAR (100) NOT NULL,
    keterangan_brg TEXT (255),
    tgl_pengadaan_brg DATE NOT NULL,
    no_pengadaan_brg VARCHAR (200) NOT NULL,
    stock_awal INT (100) NOT NULL,
    stock_masuk INT (100) NOT NULL,
    stock_keluar INT (100) NOT NULL,
    stock_akhir INT (100) NOT NULL,
    stock_limit INT (100) NOT NULL,
    file_dok VARCHAR (255) NOT NULL,
    created_by VARCHAR(255) NULL,
    updated_by VARCHAR(255) NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    INDEX idx_id_kode_a05 (id_kode_a05),
    INDEX idx_id_kode_a11 (id_kode_a11),
    INDEX idx_id_kode_a08 (id_kode_a08),
    INDEX idx_id_kode_a09 (id_kode_a09),
    INDEX idx_id_kode_a10 (id_kode_a10),
    INDEX idx_created_by (created_by),
    INDEX idx_updated_by (updated_by),

    CONSTRAINT fk_b03_kapal
        FOREIGN KEY (id_kode_a05) REFERENCES a05_dm_kapal(id_kode)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_b03_namabarang
        FOREIGN KEY (id_kode_a11) REFERENCES a11_dm_nama_brg(id_kode)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_b03_kategoribrg
        FOREIGN KEY (id_kode_a08) REFERENCES a08_dm_kategori_brg(id_kode)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_b03_jenisbrg
        FOREIGN KEY (id_kode_a09) REFERENCES a09_dm_jenis_brg(id_kode)
        ON DELETE CASCADE ON UPDATE CASCADE,

        CONSTRAINT fk_b03_golonganbrg
        FOREIGN KEY (id_kode_a10) REFERENCES a10_dm_golongan_brg(id_kode)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_b03_created_by
        FOREIGN KEY (created_by) REFERENCES a01_dm_users(id_kode)
        ON DELETE SET NULL ON UPDATE CASCADE,

    CONSTRAINT fk_b03_updated_by
        FOREIGN KEY (updated_by) REFERENCES a01_dm_users(id_kode)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;