import brotliCompress from 'https://esm.run/brotli-unicode'
import buffer from 'https://cdn.jsdelivr.net/npm/buffer/+esm'
import toBuffer from 'https://cdn.jsdelivr.net/npm/typedarray-to-buffer/+esm'
import config from "./config.js";

let Buffer = buffer.Buffer;

class Crypt {
    constructor() {
        this.enc = new TextEncoder();
        this.iv = new Uint8Array(16);
        let phpSessionIv = Uint8Array.from(this.enc.encode(window.phpSession)).subarray(0,16)
        console.debug(phpSessionIv)

        this.pbkdf2Params = {
            name: "PBKDF2",
            salt: window.crypto.getRandomValues(new Uint8Array(16)),
            iterations: 100000,
            hash: "SHA-256"
        }

        this.cryptParams = { 
            name: "AES-CTR",
            counter: this.iv, 
            length: 128
        }
    }
    
    get password() {
        return sessionStorage.getItem("password");
    }

    get gibber() {
        let gibber="";
        let uuid=window.crypto.randomUUID();

        [uuid.toLowerCase(),window.phpSession.toLowerCase()].forEach(item => {
            const letters=item.toLowerCase().matchAll("[a-z]")
                .toArray();
            
            letters.forEach(letter => {
                try {
                    let randomNum=Number(window.crypto.randomUUID().match('[1-9]')[0])
                    let randomChars=randomNum+window.crypto.randomUUID().match(`[0-${randomNum}].+?[0-9]`)[0];
                    randomChars=randomChars == undefined ? randomNum : randomChars;
                    gibber+=String(letter+randomChars+randomNum);
                } catch(e) {
                    gibber+=letter;
                }
            })
        })
        return gibber.substring(0,10);
    }

    get key() {
        const password = this.password ? this.password : this.gibber
        return window.crypto.subtle.importKey(
            "raw",
            this.enc.encode(password),
            "PBKDF2",
            false,
            ["deriveBits", "deriveKey"],
        ).then(async (keyMaterial) => {
            return await window.crypto.subtle.deriveKey(this.pbkdf2Params,keyMaterial,
                this.cryptParams,
                true,
                ["encrypt", "decrypt"]
            );
        });
    }


    async encrypt(inputElem, outputElem) {
        const startTime = performance.now();
        window.crypto.subtle.encrypt(this.cryptParams, await this.key, this.enc.encode(inputElem.value)).then(cipherdata => {
            brotliCompress.compress( new Uint8Array(cipherdata) ).then(compressed => {
                compressed = Buffer.from(this.enc.encode(compressed,"unicode")).toString("base64");
                if(compressed.length > 264) { return }
                outputElem.value = compressed;
                console.debug("Compress finished","Char length:",compressed.length, performance.now() - startTime)
            });
            console.debug("Encrypt finished", performance.now() - startTime)
        });
    }

    async decrypt(ciphertext) {
        const crDrata = sessionStorage.getItem("crypt_data");
        const startTime = performance.now();
        window.crypto.subtle.decrypt(this.cryptParams, await this.key, crDrata).then(cipherdata => {
            brotliCompress.compress( new Uint8Array(cipherdata) ).then(compressed => {
                compressed = Buffer.from(this.enc.encode(compressed,"unicode")).toString("base64");
                if(compressed.length > 264) { return }
                outputElem.value = compressed;
                console.debug("Compress finished","Char length:",compressed.length, performance.now() - startTime)
            });
            console.debug("Encrypt finished", performance.now() - startTime)
        });
    }
}

fetchEncryptedContent = async (shortCode) => fetch(configREDIRECT_URL)

document.addEventListener("DOMContentLoaded", function(e) {
    try {
        //let linkhint = document.getElementsByClassName("success")[0]
        //linkhint.innerText = linkhint.innerText + "/" + crypt.getPrivateKeyB64();
    } catch(e) { console.log(e) }

    window.phpSession = document.getElementById("session").content;

    window.c = new Crypt();

    const path = this.location.pathname;
    if(path.includes("?s")) {
        const uniqueCode = path.split("?s=")[1];
        if(uniqueCode.length != 8) { return; }
        
        const password = path.split("&p=")[1];
        if(password == undefined) { return; }

        sessionStorage.setItem("password",password);
    }
});