# Pencarian dan Sorting

## Annotation `@Searchable`

Untuk melakukan pencarian sangatlah mudah, Anda cukup menggunakan annotation `@Searchable` sebagai berikut:

```php
@Searchable({"field1", "field2"})
```

Dengan `field1` dan `field2` adalah nama property pada entity yang diberi annotation `@Searchable` tersebut.


Selanjutnya, untuk mengetahui bahwa sebuah request berisi **query string**, Semart Skeleton mendeteksi melalui URL yang dikirim yaitu dengan menyertakan GET parameter `q` para URLnya.


Sebagai contoh, Anda mengirimkan request URL `http://localhost:8000/admin/groups/?q=admin`, maka Semart Skeleton akan memperfilter secara **case insensitive** menggunakan `LIKE = '%admin%'` pada field yang ada pada `@Searchable`. 

## Annotation `@Sortable`

Untuk melakukan pencarian sangatlah mudah, Anda cukup menggunakan annotation `@Sortable` sebagai berikut:

```php
@Sortable({"field1", "field2"})
```

Dengan `field1` dan `field2` adalah nama property pada entity yang diberi annotation `@Sortable` tersebut.

Untuk melihat contoh dari penggunaan `@Searchable` dan `@Sortable`, Anda dapat melihatnya pada entity [Group](../src/Entity/Group.php)
