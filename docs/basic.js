        document.getElementById("runCodeButton").addEventListener("click", function() {
            var html1 = document.getElementById("html1").value;
            var resultEl = document.getElementById("result");
            resultEl.innerHTML = "";
            document.getElementById('test').innerHTML = "";
            var userCodes = [];
            for (var i = 1; i <= 4; i++) {
                var code = document.getElementById("code" + i).value;
                if(code !== '') {
                userCodes.push(code);
                }
            }
            var iframe = document.createElement('iframe');
            iframe.width=2; iframe.height=2;
            document.getElementById('test').appendChild(iframe);
            var codeFrame = iframe;
            var codeFrameDoc = codeFrame.contentDocument;
            var originalHTML = html1;
            codeFrameDoc.open();
            codeFrameDoc.write('<'+'html'+'><'+'body'+'><'+'div id="jsp_overdevhtml">');
            codeFrameDoc.write(html1);
            codeFrameDoc.write('</'+'div>');
            codeFrameDoc.write('<'+'script>');

            for (var i = 0; i < userCodes.length; i++) {
                codeFrameDoc.write(`
                    var userCode${i + 1} = function() {
                        ${userCodes[i]}
                    };
                `);
            }
            codeFrameDoc.write(`
                function resetHTML(html) {
                    document.getElementById('jsp_overdevhtml').innerHTML = html;
                }
            `);
            codeFrameDoc.write('</'+'script>'+'</'+'body>'+'</'+'html>');
            codeFrameDoc.close();
            for (var i = 0; i < userCodes.length; i++) {
                var startTime = performance.now();
                var runsTimes = document.getElementById('runstimes').value;
                for (var j = 0; j < runsTimes; j++) {
                    codeFrame.contentWindow['userCode' + (i + 1)]();
                    codeFrame.contentWindow.resetHTML(originalHTML);
                }
                var endTime = performance.now();
                var time = endTime - startTime;
                resultEl.innerHTML += "Code " + (i + 1) + " Execution Time: " + time + " milliseconds<br>";
            }
        });
        
                window.onload = function(e){ 
             if(window.location.hash) {
if(window.location.hash == "#run") {
        document.getElementById("runCodeButton").click();
}
}}; 

document.getElementById("mrbtn").addEventListener("click", function() {
    var d = document.getElementById("moretests");
    if (d.style.display === "none") {
        d.style.display = "block";
        document.getElementById("mrbtn").style.display = "none";
    }
});