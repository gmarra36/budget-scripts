<?php

    $base_dir = $_SERVER['HOME'];
    require ($base_dir . '/Documents/vars-budget.php');
    require ($base_dir . $functions_directory);
    $report_name = "ETrade Amount Invested";

    # Get latest date for budget in budget and set that in GET for category balances
    $settings = get_settings($ch, $base ,$budgetID);
    $oldest_budget_date = get_oldest_date($settings, $budgetID);
    $newest_budget_date = get_recent_date($settings, $budgetID);

    # Endpoint to grab all transactions for 'Credit Card Cash Rewards'
    $endpoint = "/$BUDGET_ID/accounts/$ETRADE_ACCOUNT_ID/transactions";
    curl_setopt($ch, CURLOPT_URL, $base . $endpoint);

    $transactions = json_decode(curl_exec($ch), true);

    $yearly_totals = array();

    foreach ($transactions["data"]["transactions"] as $transaction) {

        if ($transaction["payee_id"] == $ETRADE_TRANSFER_PAYEE_ID) {
        
            #var_dump($transaction);echo "\n\n\n\n\n";

            $amount = $transaction["amount"] / 1000;
            $year = explode("-", $transaction["date"])[0];

            if (array_key_exists($year, $yearly_totals)) {
                
                $yearly_totals[$year] += $amount;

            } else {

                $yearly_totals[$year] = $amount;

            }

        }

    }

    print_totals($yearly_totals, $report_name, $oldest_budget_date, $newest_budget_date);
