<?php $this->title = 'Сервис коротких ссылок'; ?>
<h1>Сократи ссылку</h1>
<input type="text" id="original-url" placeholder="Введите URL" style="width: 300px; padding: 8px;">
<button id="shorten-btn" onclick="shorten()">Сократить</button>
<div id="result" style="margin-top: 20px; color: green; font-weight: bold;"></div>
<script>
function shorten() {
    const url = document.getElementById('original-url').value;
    if (!url) return;
    fetch('/api/shorten', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'url=' + encodeURIComponent(url)
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) {
            alert('Ошибка: ' + data.error);
        } else {
            document.getElementById('result').innerHTML = 
                'Короткая ссылка: <a href="' + data.short_url + '" target="_blank">' + data.short_url + '</a>';
        }
    });
}
</script>