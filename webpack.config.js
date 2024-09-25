const path = require('path');

module.exports = {
  mode: 'development', // ou 'production' dependendo do seu caso
  entry: './app/js/index.js', // Seu ponto de entrada principal
  output: {
    filename: 'bundle.js',
    path: path.resolve(__dirname, 'dist'), // Pasta de saída para o bundle
  },
  module: {
    rules: [
      {
        test: /\.js$/, // Regra para arquivos JavaScript
        exclude: /node_modules/, // Ignora a pasta node_modules
        use: {
          loader: 'babel-loader', // Usa o babel-loader
          options: {
            presets: ['@babel/preset-env'], // Adicione presets necessários
          },
        },
      },
    ],
  },
  resolve: {
    extensions: ['.js'], // Extensões que o Webpack deve resolver
  },
};
