//ha private akkor megjelenik a password 
let privacyRadios = document.querySelectorAll("input[name='privacy']")
let displayNones = document.querySelectorAll(".display_none")
let passwordInput = document.querySelector("#password")

privacyRadios.forEach(i => {

    i.addEventListener("change", () => {

        if (i.value == "private" && i.checked){
            displayNones.forEach(j => j.style.display = "inline-block")
            passwordInput.require = true;
        } else if (i.value == "public" && i.checked){
            displayNones.forEach(j => j.style.display = "none")
            passwordInput.require = false;
        }
    })
})

//chatroomra kattintás
let chatRoomDivs = document.querySelectorAll(".chat-room-divs")

chatRoomDivs.forEach(i => {
    let id = i.getAttribute("data-room_id")
    let privacy = i.getAttribute("data-privacy")

    i.addEventListener("click", () => {

        if (privacy === "private") {
            
            // van-e már hozzáférés
            fetch(`${baseURL}/php/chathub/access_control.php?action=check_access&room_id=${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.access) {
                        
                        window.location.href = `${baseURL}/php/chatroom.php?room_id=${id}`;
                    
                    } else {
                        
                        // jelszó kérése
                        let password = prompt("Enter the password to access this private room:");
                        if (password === null) return;

                        // jelszó ellenőrzés
                        fetch(`${baseURL}/php/chathub/access_control.php`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: `action=check_password&room_id=${id}&password=${password}`
                        })
                            .then(res => res.json())
                            .then(result => {
                                if (result.success) {
                                    // Sikeres → belép
                                    window.location.href = `${baseURL}/php/chatroom.php?room_id=${id}`;
                                } else {
                                    alert("Incorrect password");
                                }
                            });
                    }
                });
        } else {
            
            window.location.href = `${baseURL}/php/chatroom.php?room_id=${id}`;
        }
    })

    //hozzáférés szinezése
    if (privacy === "private"){
        let p = i.querySelector(".privacy-p")

        fetch(`${baseURL}/php/chathub/access_control.php?action=check_access&room_id=${id}`)
            .then(res => res.json())
            .then(data => {
                if (data.access){
                    p.style.color = "rgb(0, 255, 0)"
                } else {
                    p.style.color = "red"
                }
            })
    }
})

//keresés
let search_input = document.querySelector("#flex-row-input")
let search_results = document.querySelector("#search_results")
let dropdown = document.querySelector("#search_results")

search_input.addEventListener("input", () => {
    let search_value = search_input.value.trim()

    if (search_value.length > 0){

        fetch(`${baseURL}/php/chathub/search.php`, {
            method: "POST",
            body: new URLSearchParams({
                "search_input": search_value
            })
        })

        .then(response => response.text())
        .then(data => {
            search_results.innerHTML = data
            search_results.style.display = "block"
        })

        dropdown.style.display = "block"
    } else {
        search_results.innerHTML = ""
        search_results.style.display = "none"
        dropdown.style.display = "none"
    }
})