function moji(o) {
    let m = o.nextElementSibling;
    //ads
    let h = ((o)=>{
        let l = o.value.match(/(https?:\/\/[a-z|A-Z|0-9|\-|_|%|\.|\/]{0,})/giu);
        let ml = l?((l)=>{
           return sum = l.reduce((s,e)=>{
                return s + e.length;
           },0);
        })(l):0;
        return l?{len:(l.length * 23),mlen:ml}:{len:0,mlen:ml};
    })(o);
    //zenkaku            
    let k = ((o)=>{
        let l = o.value.match(/[^\x20-\x7e]/giu);
        let ml = l?((l)=>{
           return sum = l.reduce((s,e)=>{
                return s + e.length;
           },0);
        })(l):0;
        return l?{len:(l.length * 2),mlen:l.length}:{len:0,mlen:ml};
    })(o);
    m.innerHTML = "【 " + (o.value.length + h.len - h.mlen  + k.len - k.mlen) + "文字{半角/280} 】";
    if((o.value.length + h.len - h.mlen  + k.len - k.mlen)>=280){
        m.innerHTML = "<span class='text-danger'>【 " + (o.value.length + h.len - h.mlen  + k.len - k.mlen) + "文字{半角/280} 】</span>";
    }
}
function twg(o){
    o.parentElement.remove();
}
document.getElementById("btn1").addEventListener("click", function() {
    document.getElementById("frm").insertAdjacentHTML("beforeend", `                <div class="twg form-group">
            <label for="my-textarea"></label>
            <textarea id="my-textarea" oninput="moji(this)" maxlength=500 class="tw-text form-control" name="" rows="3"></textarea>
            <p class="moji_len"></p><button onclick="twg(this)" class="btn btn-primary" type="button">削除する</button>
        </div>`);
});
document.getElementById("btn2").addEventListener("click", function() {
    let o = this;
    this.disabled = true;
    this.innerText = "ツイート中";
    document.getElementById("loading").style.display = "block";
    var data = {
        text: document.getElementsByName("text")[0].value,
        password: document.getElementsByName("password")[0].value,
        csrf: document.getElementsByName("csrf")[0].value,
        timeonoff:(()=>{
            let t = document.getElementsByName("time");
            let val = 0;
            for(let i =0 ;i < t.length;i++){
                if(t[i].checked){
                    val = Number(t[i].value);
                }
            }
            return val;
        })(),
        time:document.getElementsByName("tweets-time")[0].value
    };

    data["tw-text"] = Object.keys(document.querySelectorAll(".tw-text")).map((elm, key) => {
        return document.querySelectorAll(".tw-text")[key].value;
    });

    $.ajax({
        type: "post",
        url: "./tw-ajax.php",
        data: data,
        dataType: "json",
        success: function(response) {
            if (response.msg === "ok") {
                Object.keys(document.querySelectorAll(".tw-text")).forEach((elm, key) => {
                    document.querySelectorAll(".tw-text")[key].value = "";
                });
                o.disabled = false;
                o.innerText = "ツイートする";
                alert("OK");
            } else {
                alert("NG");
                o.disabled = false;
                o.innerText = "ツイートする";
            }
        }
    });
});