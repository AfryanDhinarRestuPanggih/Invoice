# 👋 Selamat Datang di Aplikasi Manajemen Invoice  
Halo! 👋  
Selamat datang di Aplikasi Manajemen Invoice berbasis web yang dibangun menggunakan CodeIgniter 4.
Aplikasi ini dirancang untuk membantu anda dalam mengelola pelanggan, produk, serta membuat dan mencetak invoice secara cepat dan efisien.

Nikmati kemudahan pencatatan transaksi dan pantau seluruh tagihan hanya dalam beberapa klik.
Cocok untuk pelaku UMKM, staf admin, hingga pengelola bisnis skala kecil dan menengah.

## 👥 Anggota Kelompok  
1. Charlos Louis F  
2. Afryan Dhinar Restu Panggih  
3. Andika Setiawan  

## ✨ Fitur Utama  
- Login pengguna  
- Manajemen pelanggan (CRUD)  
- Manajemen produk (CRUD)  
- Pembuatan invoice (CRUD)  
- Riwayat & detail invoice

## STORY BOARD  
### Halaman Login & Register  
<img width="1182" height="467" alt="Screenshot 2025-07-11 163019" src="https://github.com/user-attachments/assets/e54f97d0-688a-4cce-9bbe-9abc1c67c89b" />  
Pengguna tiba di halaman login aplikasi Web Invoice. Anda diminta untuk memasukkan email dan password. Setelah itu, pengguna menekan tombol 'Login' untuk masuk ke sistem. Bila belum memiliki akun, pengguna dapat mengklik link 'Daftar di sini'. Jika lupa password, tersedia link 'Lupa Password?' untuk pemulihan akun. Dan halaman pendaftaran, pengguna diminta mengisi nama lengkap, email, password, dan konfirmasi password. Setelah semua data diisi, pengguna menekan tombol 'Daftar' untuk membuat akun baru. Bila sudah memiliki akun, pengguna bisa kembali ke halaman login melalui link 'Login di sini'.  

### Dashboard  
<img width="630" height="432" alt="Screenshot 2025-07-11 163418" src="https://github.com/user-attachments/assets/7b8d8a34-5a8a-4732-bc55-baabc98c8f08" />  

Setelah berhasil login, pengguna diarahkan ke halaman Dashboard. Di sini, pengguna dapat melihat ringkasan data seperti total invoice yang telah dibuat, total pendapatan yang telah dibayarkan, serta jumlah invoice yang masih pending.  

### Halaman Produk  
<img width="1020" height="652" alt="Screenshot 2025-07-11 163549" src="https://github.com/user-attachments/assets/73ea27c5-9709-491d-92b1-4fd27fb1c85f" />  
Pada halaman Produk, pengguna dapat melihat daftar produk yang tersedia dalam sistem. Setiap baris menampilkan informasi produk seperti kode, nama, deskripsi, harga, satuan, dan status ketersediaan. Terdapat fitur pencarian di bagian atas untuk memudahkan pencarian produk tertentu.  

### Halaman Pelanggan  
<img width="1004" height="649" alt="Screenshot 2025-07-11 163710" src="https://github.com/user-attachments/assets/a3898a08-8154-4628-b695-0976336e611f" />  
Halaman Data Pelanggan menampilkan daftar pelanggan yang telah terdaftar dalam sistem. Setiap baris berisi informasi pelanggan seperti nama, email, nomor telepon, dan alamat.  

### Halaman Invoice  
<img width="1018" height="671" alt="Screenshot 2025-07-11 164031" src="https://github.com/user-attachments/assets/5329ad28-088c-4721-bf11-34d321c6cb81" />  
Halaman ini menampilkan daftar semua invoice yang telah dibuat dalam sistem. Di bagian atas terdapat filter pencarian berdasarkan nomor invoice, nama pelanggan, status invoice, serta rentang tanggal. Tiga kartu ringkasan menampilkan statistik penting yaitu total pendapatan, jumlah yang belum dibayar, dan rata-rata nilai invoice. Tabel di bawahnya menyajikan daftar invoice secara rinci, termasuk nomor invoice, nama pelanggan, tanggal dibuat, tanggal jatuh tempo

---

## MOCK UP  
### Login Dan Register
![Screenshot 2025-07-07 154759](https://github.com/user-attachments/assets/716e27c4-05d6-4167-ab18-bb4f5c66d3bc)

### Dashboard
![image](https://github.com/user-attachments/assets/5f1dddd3-96cb-4aa7-8588-008d52114829)

### Produk
![image](https://github.com/user-attachments/assets/7b8ee1ac-255e-4501-86bc-85ae22c64920)

### Customers
![image](https://github.com/user-attachments/assets/9a3e725a-bcbc-4731-bcb6-606447312112)

### Invoice
![image](https://github.com/user-attachments/assets/344fc00e-c101-49b6-968f-b4a4cd7182e3)
----

# Tampilan Aplikasi  
**🔐 Halaman Login**   
Halaman login untuk mengakses sistem manajemen invoice.  

![Screenshot 2025-07-07 155253](https://github.com/user-attachments/assets/0045fde0-c8fa-4e5d-b366-58dc6f8e2737)

**📝 Halaman Register**  
Form pendaftaran akun pengguna baru.  

![image](https://github.com/user-attachments/assets/90479992-c073-48c6-aa73-6ebed3a6b801)

**📊 Dashboard**  
Menampilkan ringkasan data pelanggan, produk, dan invoice.  

![image](https://github.com/user-attachments/assets/db6ef176-dfc0-4420-be11-9d05b46ffe01)  

Halaman dashboard menampilkan ringkasan jumlah invoice, total pendapatan, dan status invoice secara visual.   
Terdapat grafik pendapatan bulanan dan diagram donat untuk melihat distribusi status invoice (Draft, Terkirim, Dibayar, Batal) secara real-time.  

**📦 Data Produk**  
Kelola data produk: tambah, edit, hapus, dan lihat detail.  

![image](https://github.com/user-attachments/assets/ed4326d4-b130-4f20-847e-633a80e421b5)  

Menampilkan daftar produk yang tersedia dalam sistem.  
Setiap produk mencakup informasi kode, nama, deskripsi, harga, satuan, dan status aktif/tidak.  
Terdapat fitur pencarian, tombol untuk mengedit atau menghapus produk, serta opsi untuk menambah produk baru.  

**👥 Data Pelanggan**  
Manajemen data pelanggan secara lengkap.  

![image](https://github.com/user-attachments/assets/7bc05022-0b39-4a83-8b7b-38a0eff1991b)  

Menampilkan daftar data pelanggan yang tersimpan dalam sistem.
Setiap entri memuat informasi nama, email, nomor telepon, dan alamat. 
Disediakan tombol aksi untuk melihat detail, mengedit, dan menghapus data pelanggan. Juga tersedia tombol Tambah Pelanggan di kanan atas untuk input baru.  

**🧾 Data Invoice**  
Buat dan kelola invoice secara otomatis dan efisien.  

![image](https://github.com/user-attachments/assets/59de3b06-e078-4454-8886-19820929af46)  

Menampilkan daftar invoice yang telah dibuat, lengkap dengan informasi pelanggan, tanggal, total tagihan, dan status pembayaran.
Terdapat fitur pencarian berdasarkan nomor invoice/nama pelanggan, filter status, serta rentang tanggal.
Informasi ringkasan seperti total invoice, total pendapatan, total belum dibayar, dan rata-rata invoice juga ditampilkan.
Tombol aksi disediakan untuk melihat detail, mencetak, dan menghapus invoice.
