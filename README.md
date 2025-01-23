
# AWS S3 Backup Cost Calculator

This project provides an interactive web-based tool for calculating AWS S3 backup costs. The calculator is designed to estimate both storage and API request costs based on user inputs, offering flexibility and detailed breakdowns to aid business decisions.

## Features

- **Flexible Size Input**: Input the total size in MB, GB, or TB, and the script will automatically convert it to GB for calculations.
- **Backup Storage Tiers**: Select from predefined backup storage tiers, including:
  - Amazon S3 Backup - Warm Storage
  - Amazon S3 Backup - Logically Air-Gapped Vault
- **Bucket Storage Classes**: Choose bucket storage classes such as S3 Standard, Intelligent-Tiering, Glacier, and others.
- **API Call Breakdown**: Detailed cost breakdown for API calls (`PUT`, `GET`, and `LIST`), based on the number of objects and monthly backup runs.
- **Monthly Runs Multiplier**: Specify the number of complete runs per month to estimate the total API costs.
- **Responsive Design**: Clean and simple layout for easy usage.

## Installation

1. Clone this repository to your local machine or server:
   ```bash
   git clone https://github.com/disappointingsupernova/S3BackupCalculator.git
   ```
2. Place the `index.php` file on your web server. For example, if you're using Apache, place it in `/var/www/html/`.

3. Open the tool in a browser at `http://localhost/index.php` or your server's IP address.

## Usage

1. **Input Fields**:
   - **Total Size**: Specify the total size of your data and select the unit (MB, GB, or TB).
   - **Object Count**: Enter the total number of objects in the S3 bucket.
   - **Backup Storage Tier**: Choose the backup storage tier from the dropdown menu.
   - **Bucket Storage Class**: Select the bucket storage class for your S3 bucket.
   - **API Runs Per Month**: Specify the number of times backups are run in a month.
2. **View Results**:
   - Click the "Calculate" button to display a detailed breakdown of costs, including:
     - Storage Costs
     - API Costs (`PUT`, `GET`, `LIST`)
     - Total Monthly Cost

## File Structure

- `index.html`: Main HTML file containing the form, JavaScript logic, and result display.
- `style.css`: Inline styles are used in this file to keep it self-contained.
- `README.md`: Documentation for the project.

## Example Calculation

| Parameter                | Value        |
|--------------------------|--------------|
| Total Size               | 500 GB       |
| Object Count             | 1,000,000    |
| Backup Storage Tier      | Warm Storage |
| Bucket Storage Class     | S3 Standard  |
| API Runs Per Month       | 4            |

**Result**:

- **Storage Cost**: $25.00
- **PUT Requests**: $20.00
- **GET Requests**: $1.60
- **LIST Requests**: $2.00
- **Total API Cost**: $23.60
- **Total Monthly Cost**: $48.60

## Contributions

Contributions are welcome! Please open an issue or submit a pull request if you'd like to improve this project.

## License

This project is open-source and available under the MIT License.

---

Built with ❤️ for cost-conscious AWS users.
