<h2>Edit Event</h2>

<form method="POST" action="/admin/events/{{ $event->id }}/update">
    @csrf
    <input type="text" name="nama_event" value="{{ $event->nama_event }}"><br>
    <input type="text" name="lokasi" value="{{ $event->lokasi }}"><br>
    <input type="date" name="tanggal" value="{{ $event->tanggal }}"><br>
    <input type="number" name="harga" value="{{ $event->harga }}"><br>
    <button type="submit">Update</button>
</form>
