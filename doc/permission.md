# Hak Akses

## Konsep Dasar

Konsep awal security pada **Semart Skeleton** adalah bagaimana agar user dapat mengatur sendiri hak aksesnya tanpa terikat dengan developer. Untuk itu,
kami sengaja tidak menggunakan fitur **Role Hierarchy** pada Symfony dan membuat sendiri konsep security menggunakan **User**, **Group**, **Menu** dan **Role** (bukan **Role** pada Symfony),
sehingga user nantinya dapat mengubah hak akses hanya dengan mencentang list **Role** pada **Menu** dari **Group** tertentu.


Konsep ini sangat umum menurut saya dan lebih mudah dipahami ketimbang harus menggunakan **Role Hierarchy**, **Access Control List** dan segala macam yang seringkali hanya dapat dipahami oleh developer.


## Mengatur Hak Akses

## Annotation `@Permission`

## Mengatur Menu
