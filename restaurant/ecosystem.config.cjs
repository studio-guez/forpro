module.exports = {
	apps: [
		{
			name: 'restaurant',
			script: './build/index.js',
			env: {
				NODE_ENV: 'production',
				PORT: 3001
			},
			env_file: '.env.production'
		}
	]
};
