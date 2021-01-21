
fetch("http://localhost:8080/user/likes", {
    method: 'GET'
})
    .then(response => response.json())
    .then(data => {
        const userIndicator = document.querySelector('.profile-name');
        userIndicator.innerHTML = data.name;

        const likeIndicator = document.querySelector('.profile-likes');
        let likes = document.createElement('span');
        likes.innerText = data.likes;
        likeIndicator.appendChild(likes);

    })
    .catch((error) => {
        console.error('Error:', error);
    });
