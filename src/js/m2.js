document.getElementById("btn1").addEventListener("click", (e) => {
    var data = {
        text: document.getElementsByName("text")[0].value,
        password: document.getElementsByName("password")[0].value,
        csrf: document.getElementsByName("csrf")[0].value,
        mode: "load",
    };
    action(data);
});

function action(data) {
    $.ajax({
        type: "post",
        url: "./tw-ajax.php",
        data: data,
        dataType: "json",
        success: function (response) {
            if (response.msg === "ok") {
                switch (data.mode) {
                    case "edit":
                        alert("更新しました。");
                        break;
                    case "delete":
                        alert("リロードします。");
                        location.reload();
                        break;
                    default:
                        view(response);
                        break;
                }
            } else {
                alert("NG");
            }
        }
    });
}

function view(r) {
    let str = "";
    str += `
    <div class="container">
        <div class="row">
            
    `;
    Object.keys(r.value).forEach((v) => {
        str += `
            <div class="tw col-12">        
                <div class="form-group">
                    ${r.value[v].times}-${r.value[v].tw_id}
                    <textarea class="form-control tw-text" name="" rows="3">${r.value[v].tw_text}</textarea>
                    <button class="btn btn-primary tw-edit" data-id=${r.value[v].tw_id} data-time="${r.value[v].times}" type="button">編集する</button>
                    <button class="btn btn-danger del" data-id=${r.value[v].tw_id} data-time="${r.value[v].times}" type="button">削除する</button>
                </div>
            </div>            
        `;

    });
    str += `
            
        </div>
    </div>
    `;
    document.getElementById("frm").innerHTML = str.replace(/[`]{0,}/g, "");
    let tw_edit = document.getElementsByClassName("tw-edit");
    for (const key1 in tw_edit) {
        if (Object.hasOwnProperty.call(tw_edit, key1)) {
            tw_edit[key1].addEventListener("click", function () {
                let data = {
                    text: document.getElementsByName("text")[0].value,
                    password: document.getElementsByName("password")[0].value,
                    csrf: document.getElementsByName("csrf")[0].value,
                    mode: "edit",
                    tw_id: this.getAttribute("data-id"),
                    time: this.getAttribute("data-time"),
                    "tw-text": this.previousElementSibling.value
                };
               action(data);
            });
        }
    }

    let del = document.getElementsByClassName("del");
    for (const key2 in del) {
        if (Object.hasOwnProperty.call(del, key2)) {
            del[key2].addEventListener("click", function () {
                let data = {
                    text: document.getElementsByName("text")[0].value,
                    password: document.getElementsByName("password")[0].value,
                    csrf: document.getElementsByName("csrf")[0].value,
                    mode: "delete",
                    tw_id: this.getAttribute("data-id"),
                    time: this.getAttribute("data-time")
                };
                action(data);
            });
            
        }
    }
}