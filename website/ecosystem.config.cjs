module.exports = {
	apps: [
		{
			name: 'website',
			script: './build/index.js',
			env: {
				NODE_ENV: 'production',
				PORT: 3000
			},
			env_file: '.env.production'
		}
	]
};
