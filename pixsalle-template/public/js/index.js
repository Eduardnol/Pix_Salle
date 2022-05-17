$('.deletePhotoById').on('click', clickFunction);


function clickFunction(e) {

    let connect = new Connect();
    let albumId = $('#albumId').text();

    connect.deleteImageById($(e.target).attr('id'), albumId);

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
}
