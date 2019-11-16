/* Schemas */

create table products(
    display_name varchar(255) not null,
    description text null,
    slug varchar(300) not null unique comment 'This will be used in the url good practice for SEO',
    uid varchar(50) not null primary key,
    vendor_uid varchar(50) null comment 'UID of the product vendor, usually the user who created the product',
    created_at timestamp default current_timestamp,
    deleted_at timestamp null comment 'It is better to soft delete the product by simply storing the deletion date in order to hide it from response, instead of deleting it, maybe only the supper admin has the privilege delete the product definitely from the system'
) default character set = utf8;

create table images (
    uid varchar(50) not null primary key,
    imageable_id varchar(50) not null comment 'The related object_id, ex: PR345243',
    imageable_type varchar(150) not null comment 'The related object_type, ex: product',
    image_link varchar(500) not null comment 'the public image link',
    sort_index tinyint null comment 'Order index, if the product has many images, it is better to manage which one to appear first and last'
) default character set = utf8;

create table variants (
    display_name  varchar(255) not null comment 'example: colors, size ...etc',
    slug varchar(255) not null unique,
    product_uid varchar(50) not null,
    uid varchar(50) not null primary key,
    price decimal(8,2) not null comment 'price that the customers will see, pay',
    cost decimal(8,2) not null comment 'How much does the product variant cost us',
    qty TINYINT default 0 comment 'available qty',
    created_at timestamp default current_timestamp,
    foreign key (product_uid) references products(uid)
) DEFAULT CHARACTER SET = UTF8;

create table carts(
    identifier varchar(255) not null unique comment 'a unique ID that will be stored in the user cookie wich',
    content json default null comment 'cart data (products, qty ...) will be converted to json and persisted into database',
    instance varchar(100) not null default 'default' comment 'This column is very useful zhen planning to add wishlist or give the users the ability to store create many carts, it is more flexible and well known in the ecommerce services',
    last_updated_at timestamp default current_timestamp
)default character set utf8;
