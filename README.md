# XTIMES

Extension code:
https://github.com/cointimes-app/xtimes-extension

## How We Maintain Data Privacy

1. **Generation of an Access Key for Anonymous Authentication**
   - Upon installation, we generate a unique access key for the user, allowing account recovery.

2. **Two-Stage Data Collection**
   - User data is collected in two different stages to ensure anonymity.

    2.1. **Anonymous Data Transmission to AI**
       - The extension anonymously sends the titles of the user's recent browser history without any association to the user.
       - The artificial intelligence processes this data and provides insights to the client.

    2.2. **Saving Insights and Payment with XTIMES**
       - With the AI-generated insights, the extension calls an endpoint to save these insights associated with the user.
       - Payment for the data is made through a pool system to ensure anonymity.

5. **Pool System to Ensure Anonymity**
   - All deposits into the pool are of the same amount, and it is possible to create multiple pools.
   - Deposits are not directly associated with the user. In the `pool_user` table, we only associate that the user has the right to withdraw an amount from the pool.
   - Each pool has the same payment amount for users.

6. **Anonymity Logic in the `pool_user` Table**
   - We use UUIDs instead of incremental IDs.
   - We do not save dates.

7. **Withdrawal Process**
   - The user can withdraw on the Stellar network by providing their wallet address, which will not be associated with the data.

## Security Improvements

1. **Phone Validation with Zero-Knowledge Proof**
   - Implement phone validation using zero-knowledge proofs to ensure that it is indeed a legitimate user.

2. **Implementation of Worldcoin**
   - Implement Worldcoin to ensure that the extension is being used by a real person.
## Flow
<img width="1307" alt="flow" src="https://github.com/cointimes-app/xtimes-backend/assets/12894905/4fc9f2de-83b1-4477-9210-14f23d9b77e5">

## Database models
<img width="1149" alt="Database models" src="https://github.com/cointimes-app/xtimes-backend/assets/12894905/d0f97c95-05d1-4298-af8a-9e68c7ff0ced">

<img width="879" alt="Captura de Tela 2024-05-31 às 11 58 52" src="https://github.com/cointimes-app/xtimes-backend/assets/12894905/4f14048d-ab31-425b-81fb-ece902dd0324">


## Complete Solution:
In our roadmap, we plan to develop a simplified payment method for data, where businesses can purchase user data with fiat currencies, using Anchors to convert fiat to USDC. Although we have not yet implemented the data purchasing component, it is essential for our business's functionality, with Stellar being our fundamental base.

<img width="882" alt="Captura de Tela 2024-05-31 às 11 59 06" src="https://github.com/cointimes-app/xtimes-backend/assets/12894905/6b459489-7448-4d3a-9b85-3ef0e66fd2ba">



