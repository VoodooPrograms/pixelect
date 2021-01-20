fetch('http://localhost:8080/pictures/all', {
    method: 'GET'
})
    .then(response => response.json())
    .then(data => {
        console.log('Success:', data);

        const columns = document.querySelectorAll('.gallery-column');
        let columnIndex = 0;
        data.pictures.forEach((picture) => {
            let pictureImg = createPictureElement(picture);
            let thumbImg = createThumbElement(picture.likes, picture.id);
            let item = document.createElement('div');
            item.classList.add('gallery-item');

            item.appendChild(pictureImg);
            item.appendChild(thumbImg);
            columns[columnIndex].appendChild(item);
            columnIndex++;
            if (columnIndex >= columns.length) columnIndex = 0;
        })
    })
    .catch((error) => {
        console.error('Error:', error);
    });

function createPictureElement(pictureData) {
    let canvas = elt("canvas");
    let picture = Object.assign(new Picture(), JSON.parse(pictureData.data))
    drawPicture(picture, canvas, scale);

    let img = document.createElement('img');
    img.src = canvas.toDataURL();

    return img;
}

function createThumbElement(thumbs, picture_id) {
    let thumbContainer = document.createElement('div');
    let heartImg = document.createElement('img')
    let thumb = document.createElement('span')

    heartImg.src = 'app/User/Public/assets/icons/heart.svg'
    thumb.innerHTML = thumbs;

    heartImg.classList.add('picture-hearts-icon');
    thumbContainer.classList.add('picture-hearts');

    thumbContainer.dataset.picture_id = picture_id;

    thumbContainer.addEventListener('click', (thumbClicked) => {
        let form = new FormData();
        form.append("like_id", thumbClicked.target.closest('div').dataset.picture_id)
        fetch('http://localhost:8080/likes/add', {
            method: 'POST',
            body: form,
        })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                if (data.message === '')
                    thumbClicked.target.parentNode.querySelector('span').innerHTML++;
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    });

    thumbContainer.appendChild(heartImg);
    thumbContainer.appendChild(thumb);

    return thumbContainer;
}