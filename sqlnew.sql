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