#!/usr/bin/make -f

CLIENT_NAME=johndoe
CLIENT_KEY_PATH=~/.ssh/id_rsa

main: info

.PHONY: csr pkcs12 info
info:
	@echo " Create cert bundle for '${CLIENT_NAME}' using ${CLIENT_KEY_PATH} priv key"
	@echo " Recipes and running order:"
	@echo "	1. ca.key"
	@echo "	2. ca.crt"
	@echo "	3. csr      -> create ${CLIENT_NAME}.csr"
	@echo "	4. sign     -> create ${CLIENT_NAME}.crt"
	@echo "	5. bundle   -> create ${CLIENT_NAME}.pkcs12"

ca.key:
	openssl genrsa -out ca.key 4096

ca.crt: ca.key
	openssl req -new -x509 -days 3650 -key ca.key -out ca.crt

csr:
	openssl req -new -key ${CLIENT_KEY_PATH} -out ${CLIENT_NAME}.csr

sign:
	openssl x509 -req -days 365 -in ${CLIENT_NAME}.csr -CA ca.crt -CAkey ca.key -set_serial 01 -out ${CLIENT_NAME}.crt

pkcs12:
	openssl pkcs12 -export -out ${CLIENT_NAME}.pkcs12 -inkey ${CLIENT_KEY_PATH} -in ${CLIENT_NAME}.crt -certfile ca.crt
