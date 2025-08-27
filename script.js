import brotliCompress from 'https://esm.run/brotli-unicode'

document.brotliCompress = brotliCompress;

class Crypt {
    constructor() {
        this.enc = new TextEncoder();
        this.iv = new Uint8Array(16);
        let phpSessionIv = new Uint8Array(this.enc.encode(window.phpSession));
        this.iv.set(phpSessionIv);
        console.debug(this.iv)

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
    
    get gibber() {
        let gibber="";
        let uuid=window.crypto.randomUUID();

        [uuid.toLowerCase(),window.phpSession.value.toLowerCase()].forEach(item => {
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

    async encrypt(inputElem, outputElem) {
        const password = this.password ? this.password : this.gibber
        let key = await window.crypto.subtle.importKey(
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
        window.crypto.subtle.encrypt(this.cryptParams,key,this.enc.encode(inputElem.value)).then(cipherdata => {
            brotliCompress.compress(new Uint8Array(cipherdata)).then(compressed => {
                compressed = this.enc.encode(compressed,"unicode").toHex();
                if(compressed.length > 264) { return }
                outputElem.value = compressed;
            });
        });
    }

    async decrypt(ciphertext) {
        
    }
}

document.addEventListener("DOMContentLoaded", function(e) {
    try {
        //let linkhint = document.getElementsByClassName("success")[0]
        //linkhint.innerText = linkhint.innerText + "/" + crypt.getPrivateKeyB64();
    } catch(e) {}
    window.c = new Crypt();
    console.log(e)
});


function decrypt_url(key) {
    
}