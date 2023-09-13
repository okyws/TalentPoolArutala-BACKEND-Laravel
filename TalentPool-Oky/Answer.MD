# knowledge base

1. Apa yang anda ketahui tentang Rest API?
2. Apa yang anda ketahui tentang Server side and Client side processing?
3. Apa yang anda ketahui tentang Monolith dan Microservices, berikan contohnya?
4. Apa yang anda ketahui tentang Design pattern inversion of Control serta Dependency Injection?
5. Apa yang anda ketahui tentang Java programming dan Spring framework khususnya spring-boot?

# Answer

1. Apa yang anda ketahui tentang Rest API?

> Rest API adalah singkatan dari Representational State Transfer Application Programming Interface. Ini adalah cara untuk membuat aplikasi yang bisa berkomunikasi dengan aplikasi lain melalui internet dengan menggunakan protokol HTTP. Rest API memungkinkan kita untuk mengakses dan memanipulasi data dari aplikasi lain dengan mudah dan cepat. Misalnya, kita bisa menggunakan Rest API untuk mendapatkan informasi cuaca, berita, atau media sosial dari aplikasi lain tanpa harus menginstal atau membuka aplikasi tersebut.

> Jadi, Rest API itu mirip seperti jembatan komunikasi antara aplikasi atau sistem yang berbeda. Contoh sederhananya, saya punya aplikasi A dan aplikasi B yang perlu saling berbicara dan bertukar data. Nah, supaya apilkasi A dan B bisa saling berkomunikasi kita bisa menggunakan Rest API untuk mengkomunikasikan antara aplikasi A dan B untuk berkomunikasi.

> Cara kerjanya mirip seperti saat mengirim pesan lewat WhatsApp. kita bisa minta teman / seseorang mengirim foto atau pesan tertentu, dan temanmu akan balas sesuai permintaan yang kita request tadi. Di Rest API juga punya jenis-jenis pesan yang standar / bisa disebut method, seperti GET (mintak data), POST (kirim data baru), PUT (update data), dan DELETE (hapus data), dan juga ada standar method yang lainnya juga. namun umumnya yang biasa kita pakai yaitu yang tadi sudah disebutkan sebelumnya.

> Data yang dikirim dan diterima biasanya pakai format yang disebut JSON atau XML, bahasa tersebut merupakan salah satu bahasa yang bisa dimengerti oleh sistem komputer. Jadi, Rest API itu ngebantu aplikasi atau sistem berbeda-beda bisa saling ngobrol / berkomunikasi dan bertukar data dengan cara yang terstruktur.

2. Apa yang anda ketahui tentang Server side and Client side processing?

-   Server-side Processing:

    > Server-side processing itu seperti ketika kita pesen makanan di restoran. kita cuma kasih tau pelayan apa yang mau dipesan, kemudian pelayan tersebut membawa pesanannya ke dapur. Lalu di dapur, ada chef yang akan bekerja untuk menghidangkan makanan yang dipesan tadi. Setelah pesannannya itu jadi, pelayan akan membawa makanan yang telah dibuat oleh cheft ke meja pelanggan. Nah jadi server side itu kurang lebih seperti itu. kita bisa nikmatin hasil akhirnya, tapi gak tau proses dibelakangnya bagaimana. Nah, dalam dunia teknologi, Server-side processing itu seperti dapur tadi. Server itu kayak chef yang punya tugas utama memproses data. Jadi, ketika kita melakukan sesuatu di aplikasi atau situs web, seperti mengirim pesan atau mengisi formulir, data yang di kirim akan dikirimkan ke server. Server ini akan melakukan segala macam pemrosesan yang diperlukan, seperti nyimpen data, ngitung angka, atau bikin halaman web keren. Setelah semuanya jadi, server ini akan mengirim hasilnya balik ke perangkat milik kita.

-   > Contohnya, ketika kita nge-tweet di Twitter, pesannya dikirim ke server Twitter untuk disimpan dan dibagi ke pengguna lain yang lihat timeline kamu.

-   Client-side processing

    > Sedangkan Client-side processing kayak mirip seperti kita yang memasak sendiri di rumah. Kita yang pilih bahan-bahannya, cara masaknya, ngecek rasanya, dll. semuanya kita lakukan prosesnya di dapur sendiri. kita juga bisa ngontrol hasil akhirnya, dan juga bisa ngerasain prosesnya. Dalam dunia teknologi, Client-side processing adalah saat perangkat kita, seperti komputer atau browser yang dipakai untuk membantu proses data. Jadi, ketika kita klik sebuah tautan, isi formulir, atau berinteraksi dengan situs web, browser akan ngebantu tugas-tugas dalambentuk tampilan halaman, periksa data, atau layanin permintaan kita sebagai pengguna. Ini yang bikin situs web atau aplikasi terasa cepat dan bisa tampilin halaman dengan cara yang end user inginkan.

-   > Contohnya, ketika klik sebuah tautan di situs berita, halaman berita baru akan muncul langsung tanpa harus nunggu server, karena browser kamu yang bantu tampilkan halaman itu.

-   > Jadi, bedanya server-side processing dan client-side processing itu seperti perbedaan pesen makanan dengan masak sendiri. Server-side processing itu prosesnya dilakukan di dalam server, jauh dari mata kamu. Sedangkan Client-side processing itu prosesnya di perangkat kamu,

3. Apa yang anda ketahui tentang Monolith dan Microservices, berikan contohnya?

> Monolith dan Microservices adalah dua gaya arsitektur yang berbeda untuk membangun aplikasi. Monolith itu seperti sebuah aplikasi yang dibangun sebagai satu kesatuan yang besar dan mandiri, sedangkan Microservices itu aplikasi yang dibangun dari banyak layanan kecil yang saling berhubungan. Contohnya, kalau kamu bikin aplikasi toko online, kamu bisa pilih mau pake Monolith atau Microservices.

> Kalau pilih Monolith, artinya kita akan membuat satu kode besar yang ngurus semua fitur di aplikasinya, seperti tampilan, keranjang belanja, pembayaran, pengiriman, dan lain-lain. tetapi kita cuma punya satu database untuk nyimpen semua datanya. kita juga cuma punya satu file untuk nge-deploy aplikasi kamu ke server. Ini enaknya sih simpel dan gampang di awal-awal proyek, tapi lama-lama bisa jadi ribet dan susah diurus.

> Kalau kamu pilih Microservices, artinya kita akan membuat banyak layanan kecil yang akan mengurus masing-masing fitur di aplikasi tersebut. Misalnya, ada layanan untuk tampilan, layanan untuk keranjang belanja, layanan untuk pembayaran, dan seterusnya. Tiap layanan punya kode dan database sendiri-sendiri. selain itu, juga bisa nge-deploy tiap layanan secara terpisah ke server. Ini enaknya sih fleksibel dan mudah diubah-ubah sesuai kebutuhan, tapi juga butuh skill dan pengalaman yang lebih tinggi. Contoh nyata Microservices bisa kita lihat di aplikasi seperti Netflix. Mereka punya layanan terpisah untuk manajemen akun, rekomendasi film, dan pemrosesan pembayaran. Setiap layanan ini bekerja sendiri dan bisa diperbarui tanpa harus memengaruhi layanan lainnya.

> Jadi, Monolith itu jika diibaratkan seperti rumah yang dibangun dari satu bata besar yang susah dipindah-pindah atau diubah-ubah. Microservices itu kayak rumah yang dibangun dari banyak bata kecil yang bisa dipindah-pindah atau diubah-ubah sesuai selera / bisa dibilang mirip seperti kita menyusun sebuah puzzle.

4. Apa yang anda ketahui tentang Design pattern inversion of Control serta Dependency Injection?

> Berdasarkan informasi yang saya dapatkan dan saya baca, Inversion of Control (IoC) adalah ide dasar yang mengubah cara kita biasanya berpikir tentang bagaimana suatu program atau aplikasi berjalan. Biasanya, saat kita membuat program, kita yang mengendalikan alur jalannya. Misalnya, kita menentukan urutan perintah yang harus dilakukan oleh program kita.

> Namun, dalam IoC, kita memberikan kendali kepada sesuatu yang lebih besar, seperti framework atau sistem. Ini berarti kita tidak lagi mengendalikan secara langsung bagaimana program berjalan. Sebaliknya, kita memberi tahu sistem apa yang kita inginkan, dan sistem ini yang akan mengatur dan mengendalikan alur program sesuai dengan apa yang kita minta. Jadi, intinya IoC adalah tentang memberikan kontrol atas alur program kepada sesuatu yang lebih besar atau sistem, sehingga kita tidak perlu mengatur semuanya secara langsung. Ini membuat kode kita lebih modular, mudah dikelola, dan lebih fleksibel untuk perubahan.

> Sedangkan Dependency Injection adalah salah satu cara untuk menerapkan IoC, di mana kita menyerahkan tanggung jawab untuk membuat dan menyediakan objek-objek yang dibutuhkan oleh kode kita kepada komponen lain, seperti container atau assembler. Dengan DI, kita tidak perlu membuat objek-objek secara langsung di dalam kode kita, tapi cukup menyatakan ketergantungan yang kita butuhkan. Komponen lain yang akan menginjeksi objek-objek tersebut ke kode kita melalui konstruktor, setter, atau metode lain.

> Jadi, intinya IoC itu adalah cara untuk membuat kode kita lebih mandiri dari objek-objek lain, sedangkan DI itu adalah cara untuk menyuntikkan objek-objek yang dibutuhkan oleh kode kita tanpa harus membuatnya sendiri. Mohon maaf jika sekiranya pemahaman saya masih salah, karena untuk IoC sendiri saya baru pertama kali mengetahuinya.

5. Apa yang anda ketahui tentang Java programming dan Spring framework khususnya spring-boot?

> Java sendiri merupakan sebuah bahasa pemrograman yang ekosistemnya sudah sangat mature dan juga support multiplatform, bahsa pemrograman java sendiri sebenarnya bisa digunakan untuk membuat sebuah program baik berbasis web, desktop, atau pun aplikasi mobile. konsep bahasa pemrogramannya juga kurang lebih mirip seperti yang ada dibahasa lain, tetapi sejauh yang pernah saya pelajari data strucktur di dalam java sendiri memiliki banyak ragamnya jika saya bandingkan dengan javascript (NodeJS). sedangkan untuk spring framework itu merupakan kerangka untuk pengembangan aplikasi java dan spring boot sendiri adalah bagian dari Spring Framework yang dirancang untuk membuat pengembangan aplikasi lebih cepat dan lebih mudah. Jika menggunakan native mungkin akan memerlukan waktu pengembangan yang lumayan sedangkan jika menggunakan framework akan memakan waktu yang tidak terlalu lama terlebih didalam frameworknya sendiri kita sebagai developer diberikan kemudahan dalam pengembangannya karena didalamnya banyak library, dll yang akan memudahkan kita dalam mengembangkan sebuah project dan juga membuat pengembangannya lebih efisien.
