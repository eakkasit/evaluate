<?php if (isset($elements) && !empty($elements)) { ?>
	<!-- Optional JavaScript -->
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
			integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
			crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
			integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
			crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
			integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
			crossorigin="anonymous"></script> -->

	<script>

        // REF
        // * https://www.google.com/intl/en/chrome/demos/speech.html
        // * https://mdn.github.io/web-speech-api/speech-color-changer/

        var final_transcript = '';
        var recognizing = false;
        var ignore_onend;
        var start_timestamp;
        var lastDebounceTranscript;

        // utility tools
        var two_line = /\n\n/g;
        var one_line = /\n/g;

        function linebreak(s) {
            return s.replace(two_line, '<p></p>').replace(one_line, '<br>');
        }

        var first_char = /\S/;

        function capitalize(s) {
            return s.replace(first_char, function (m) {
                return m.toUpperCase();
            });
        }

        function showInfo(msg) {
            $('<?php echo $elements['info']; ?>').html(msg);
        }

        function upgrade() {
            alert('เบราว์เซอร์ไม่รองรับ');
        }

        function startButton(event) {
            if (recognizing) {
                recognition.stop();
                return;
            }
            final_transcript = $('<?php echo $elements['output']; ?>').val();
            recognition.lang = 'th-TH';
            recognition.start();
            ignore_onend = false;
            //final_span.innerHTML = '';
            //interim_span.innerHTML = '';
            showInfo('ข้อมูลไมโครโฟนได้รับอนุญาต');
            start_timestamp = event.timeStamp;
        }
        
        if (typeof webkitSpeechRecognition !== 'undefined'){
            $('#btn-transcribe').removeClass('btn-primary')
            $('#btn-transcribe').removeClass('btn-default')
            $('#btn-transcribe').addClass('btn-primary')
            $('#btn-transcribe').attr('disabled',false)
            // initialize recognition
        var SpeechRecognition = SpeechRecognition || webkitSpeechRecognition || mozSpeechRecognition || msSpeechRecognition || oSpeechRecognition
         
         var recognition = new SpeechRecognition();
 
         recognition.continuous = true;
         recognition.interimResults = true;
 
         recognition.onstart = function () {
             recognizing = true;
             showInfo('พูดข้อมูลตอนนี้');
         };
 
         recognition.onerror = function (event) {
             if (event.error == 'no-speech') {
                 showInfo('ไม่มีข้อมูลคำพูด');
                 ignore_onend = true;
             }
             if (event.error == 'audio-capture') {
                 showInfo('ไม่มีข้อมูลไมโครโฟน');
                 ignore_onend = true;
             }
             if (event.error == 'not-allowed') {
                 if (event.timeStamp - start_timestamp < 100) {
                     showInfo('ไมโครโฟนถูกปิดกั้น');
                 } else {
                     showInfo('ไมโครโฟนถูกปฏิเสธ');
                 }
                 ignore_onend = true;
             }
         };
 
         recognition.onend = function () {
             recognizing = false;
             if (ignore_onend) {
                 return;
             }
             if (!final_transcript) {
                 showInfo('ข้อมูลเริ่มต้น');
                 return;
             }
             showInfo('');
             /* select all text
             if (window.getSelection) {
               window.getSelection().removeAllRanges();
               var range = document.createRange();
               range.selectNode(document.getElementById('final_span'));
               window.getSelection().addRange(range);
             }
             */
         };
 
         recognition.onresult = function (event) {
             var interim_transcript = '';
             if (typeof (event.results) == 'undefined') {
                 recognition.onend = null;
                 recognition.stop();
                 upgrade();
                 return;
             }
             for (var i = event.resultIndex; i < event.results.length; ++i) {
 
                 var transcript = event.results[i][0].transcript;
                 var confidence = event.results[i][0].confidence;
                 var isFinal = event.results[i].isFinal && (confidence > 0);
 
                 if (isFinal) {
                     /*
                     // check duplicate result on android
                     if(transcript+confidence == lastDebounceTranscript) { return; }
                     lastDebounceTranscript = transcript+confidence;
                     console.log(lastDebounceTranscript);
                     */
                     final_transcript += transcript;
                 } else {
                     interim_transcript += transcript;
                 }
             }
             final_transcript = capitalize(final_transcript);
             //final_span.innerHTML = linebreak(final_transcript);
             //interim_span.innerHTML = linebreak(final_transcript);
             $('<?php echo $elements['output']; ?>').val(linebreak(final_transcript));
             if (final_transcript || interim_transcript) {
                 // do something
             }
         };
 
         // handle transcribe button
         $('<?php echo $elements['button']; ?>').click((evt) => {
             let thiz = evt.target;
 
             if ($(thiz).hasClass('btn-primary')) { // press to start
                 // ui
                 $(thiz).removeClass('btn-primary');
                 $(thiz).addClass('btn-success');
 
                 // start
                 startButton(evt);
             } else { // press to stop
                 // ui
                 $(thiz).removeClass('btn-success');
                 $(thiz).addClass('btn-primary');
 
                 // stop
                 recognizing = false;
                 recognition.stop();
             }
         });
        }else{
            $('#btn-transcribe').removeClass('btn-primary')
            $('#btn-transcribe').removeClass('btn-default')
            $('#btn-transcribe').addClass('btn-default')
            $('#btn-transcribe').attr('disabled',true)
        }
        

	</script>
<?php } ?>
