window.onload = () => {
    let zip = document.querySelector('#localization_zip');

    zip.addEventListener('input', function() {
        let form = this.closest('form');
        let data = this.name + '=' + this.value;
        fetch(form.action, {
            method: form.getAttribute('method'),
            body: data,
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset:utf-8"
            }
        })
        .then(response => response.text())
        .then(html => {
            let content = document.createElement('html');
            content.innerHTML = html;
            let newSelect = content.querySelector('#localization_city');
            document.querySelector('#localization_city').replaceWith(newSelect);
        })
        .catch(error => {
            console.log(error)
        });
    });
}; 