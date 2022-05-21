$(document).ready(function () {
    $('.deletePhotoById').on('click', clickFunction);
    $('.buttonSubmitBlogEntry').on('click', submitFormEntry);
    $('#createQrCode').on('click', createQrCode);
});


function createQrCode() {

    let url = window.location.href;
    let data = JSON.stringify({
        "symbology": "QRCode",
        "code": url
    });

    let xhr = new XMLHttpRequest();
    xhr.withCredentials = true;

    xhr.addEventListener("readystatechange", function () {
        if (this.readyState === 4) {
            console.log(this.responseText);
        }
    });

    xhr.open("POST", "http://localhost:8020/BarcodeGenerator");
    xhr.setRequestHeader("content-type", "application/json");
    xhr.setRequestHeader("accept", "image/png");

    xhr.send(data);


}

function clickFunction(e) {

    let connect = new Connect();
    let albumId = $('#albumId').text();

    connect.deleteImageById($(e.target).attr('id'), albumId);

}

function submitFormEntry(e) {
    console.log("Exito");

    const array = $('#formSubmitBlogEntry').serializeArray();
    array.push({name: "userId", value: $('#userId').text()});
    console.log(array)
    let json = {};
    $.each(array, function () {
        json[this.name] = this.value || "";
    });
    console.log(json);
    let connect = new Connect();
    connect.submitFormEntry(json);

}

const baseUrl = "http://localhost:8030"

class Connect {
    async deleteImageById(id, albumId) {
        let url = baseUrl + `/portfolio/album/${albumId}`
        const response = await fetch(url, {
            method: "DELETE",
            body: JSON.stringify({
                imageId: id
            }),
            headers: {'Content-Type': 'application/json'},
        })
        const data = response.json();
        //Now its time to check the error codes
        if (response.status == 500) {
            return "Bad Parameters";
        }
        if (response.status == 200) {
            //Process response
            return "";
        }
    }

    async submitFormEntry(json) {
        let url = baseUrl + `/api/blog`
        const response = await fetch(url, {
            method: "POST",
            body: JSON.stringify(json),
            headers: {'Content-Type': 'application/json'},
        })
        // const data = response.json();
        //Now its time to check the error codes
        if (response.status == 500) {
            return "Bad Parameters";
        }
        if (response.status == 200) {
            //Process response
            return "";
        }
    }
}
