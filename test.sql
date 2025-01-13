-- สร้างฐานข้อมูล coffee
CREATE DATABASE coffee;

-- ใช้งานฐานข้อมูล coffee
USE coffee;

-- สร้างตาราง hero
CREATE TABLE hero (
    id_hero INT AUTO_INCREMENT PRIMARY KEY,
    vdo_hero VARCHAR(255) NOT NULL
);

-- สร้างตาราง about
CREATE TABLE about (
    id_about INT AUTO_INCREMENT PRIMARY KEY,
    name_about VARCHAR(255) NOT NULL,
    detail_about TEXT NOT NULL,
    img1_about VARCHAR(255),
    img2_about VARCHAR(255)
);

-- สร้างตาราง facts
CREATE TABLE facts (
    id_facts INT AUTO_INCREMENT PRIMARY KEY,
    text1_facts TEXT NOT NULL,
    text2_facts TEXT NOT NULL,
    text3_facts TEXT NOT NULL
);

-- สร้างตาราง ingredients (ส่วนประกอบ)
CREATE TABLE ingredients (
    id_ingredients INT AUTO_INCREMENT PRIMARY KEY,
    img_ingredients VARCHAR(255) NOT NULL,
    name_ingredients VARCHAR(255) NOT NULL,
    detail_ingredients TEXT NOT NULL
);

-- สร้างตาราง product
CREATE TABLE product (
    id_product INT AUTO_INCREMENT PRIMARY KEY,
    img1_product VARCHAR(255),
    img2_product VARCHAR(255),
    img3_product VARCHAR(255),
    detail1_product TEXT NOT NULL,
    detail2_product TEXT,
    detail3_product TEXT,
    img4_product VARCHAR(255),
    img5_product VARCHAR(255),
    price_product DECIMAL(10, 0) NOT NULL
);

-- สร้างตารางกลาง product_ingredients เพื่อเชื่อม product กับ ingredients
CREATE TABLE product_ingredients (
    id_product INT NOT NULL,
    id_ingredient INT NOT NULL,
    PRIMARY KEY (id_product, id_ingredient),
    FOREIGN KEY (id_product) REFERENCES product(id) ON DELETE CASCADE,
    FOREIGN KEY (id_ingredient) REFERENCES ingredients(id) ON DELETE CASCADE
);

-- สร้างตาราง review
CREATE TABLE review (
    id_review INT AUTO_INCREMENT PRIMARY KEY,
    name_review VARCHAR(255) NOT NULL,
    position_review VARCHAR(255) NOT NULL,
    review TEXT NOT NULL
);

-- สร้างตาราง satisfaction (ความพอใจ)
CREATE TABLE satisfaction (
    id_satisfaction INT AUTO_INCREMENT PRIMARY KEY,
    method_satisfaction TEXT NOT NULL,
    maintenance_satisfaction TEXT NOT NULL,
    caution_satisfaction TEXT NOT NULL,
    img1_satisfaction VARCHAR(255),
    img2_satisfaction VARCHAR(255)
);
