#lightspeed-php

##What is this ?
lightspeed-php is a companion app for the bookWeb Development in the Cloud: Bootstrapping an Enterprise-grade Website on a Shoestring, available on Amazon at http://www.amazon.com/dp/B00J49LBUQ.  

##What you get
The app is a working web application with one important caveat: You will need to do some API configurations. Mostly, this means adding your own API keys in the /app/config/bootstrap/constants.php file. It also means adding certs in their appropriate places in each library in /app/libraries. There is a small text file in each location where this is needed for APIs that are included that need a cert. If you don't include the cert for a library that needs one, that API won't work. Certs are provided by the API, so you'll get one when you download their SDK. 

You will probably be better off bringing in your own library for each API, although some, like Cloudinary, have some issues with the way they create their directory structures, so be sure to make a back up of the library I've provided. I have set up the APIs included here to work with the Lithium PHP kernel. 

The app SHOULD work provided you have the API libraries set up properly. The app includes configurations for *IX-based Apache and NGINX web servers, and Windows. 

More documentation is coming. Feel free to fork off this sample app and do more with it. This app is completely open source.

THIS SOFTWARE IS PROVIDED "AS IS" AND ANY EXPRESSED OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL CHUCK WHITE, WYANET LLC, OR ANY CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

Without limiting the foregoing, Wyanet LLC and Chuck White make no warranty that:

* the software will meet your requirements.
* the software will work uninterrupted, be secure or error-free.
* the results that may be obtained from the use of the software will be effective, accurate or reliable.
* the quality of the software will meet your expectations.
* any errors in the software found in this software will be corrected.

Software and its documentation made available on the this GitHub web site could include technical or other mistakes, inaccuracies or typographical errors. In addition, other contributors may make changes to the software or documentation and introduce errors.

The use of the software downloaded through this site is done at your own discretion and risk and with agreement that you will be solely responsible for any damage to your computer system or loss of data that results from such activities. No advice or information, whether oral or written, obtained by you from Chuck White, Wyanet LLC, its website or any of its companion websites, or its contributors, shall create any warranty for the software.
