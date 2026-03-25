<h2>Tambah Event</h2>

<form method="POST" action="/admin/events">
    @csrf
    <input type="text" name="nama_event" placeholder="Nama Event"><br>
    <input type="text" name="lokasi" placeholder="Lokasi"><br>
    <input type="date" name="tanggal"><br>
    <input type="number" name="harga"><br>
    <button type="submit">Simpan</button>
</form>
