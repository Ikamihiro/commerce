# Integração com a Mercado pago

## Tabelas
- courses
	- id
	- name
	- description
	- amount

- students
	- id
	- name
	- email
	- document

- payments
	- id
	- data [json]
	- status [pending, approved, authorized, in_process, in_mediation, rejected, cancelled, refunded, charged_back]
	- amount
	- installments

- orders
	- id
	- course_id
	- customer_id
	- payment_id
	- status [approved, pending, cancelled]
	
	
## Fluxos

### Fluxo de compra
* O usuário confirma a compra
	* Salva o student
	* Salva o order
	* Cria o payment na Mercado Pago
		* Na verdade, reserva o valor
	* Redireciona o usuário para outra tela.
	
### Fluxo do Webhook
* Notificação chega no Webhook
	* Valida a requisição

	* Não é payment?
		* Morre aqui.
	
	* Busca na API do Mercado Pago os dados do payment pelo ID (pois ele devolve no webhook apenas o ID)
	* Não existe o payment?
		* Morre aqui.

	* Atualiza os dados do payment

	* Foi autorizado?
		* Captura a reserva do pagamento
		* Morre aqui.
	
	* Foi aprovado?
		* Aprova o order
		* Envia para fila do Moodle
		* Notifica o student
		* Morre aqui.
	
	* Foi cancelado ou estornado?
		* Cancela o order
		* Cancela a matrícula no Moodle (???)
		* Notifica o student
		* Morre aqui.

